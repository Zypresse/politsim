<?php

namespace app\models\factories;

use app\components\MyMathHelper,
    app\components\MyHtmlHelper,
    app\components\TaxPayer,
    app\models\Unnp,
    app\models\factories\proto\FactoryProto,
    app\models\factories\proto\FactoryProtoKit,
    app\models\resurses\Resurse,
    app\models\objects\UnmovableObject,
    app\models\objects\canCollectObjects,
    app\models\Place,
    app\models\resurses\ResurseCost,
    app\models\Dealing,
    app\models\Region;

/**
 * Фабрика/завод/сх-предприятие. Таблица "factories".
 *
 * @property integer $id
 * @property integer $proto_id
 * @property integer $builded
 * @property integer $holding_id
 * @property integer $region_id
 * @property integer $status Статус работы: -1 - unbuilded, -2 - build stopped, 0 - undefined, 1 - active, 2 - stopped, 3 - not enought resurses, 4 - autostopped, 5 - not enought workers, 6 - not have license
 * @property string $name
 * @property integer $size
 * @property integer $manager_uid
 * @property double $eff_region
 * @property double $eff_workers
 * @property double $balance
 * 
 * @property integer $IAmPlace
 * 
 * @property proto\FactoryProto $proto Тип фабрики
 * @property \app\models\Holding $holding Компания-владелец
 * @property \app\models\Region $region Регион, в котором она находится
 * @property \app\models\User $manager Управляющий
 * @property \app\models\Population[] $workers Рабочие
 * @property FactoryWorkersSalary[] $salaries Установленные зарплаты рабочих
 * @property \app\models\resurses\Resurse[] $storages Ресурсы на складе
 * @property \app\models\Vacancy[] $vacancies 
 * @property \app\models\Vacansy[] $vacansiesWithSalaryAndCount Актуальнаые вакансии
 * @property \app\models\Vacansy[] $vacansiesWithSalary Потенцальные вакансии
 * @property ResurseCost[] $resurseCosts
 * @property FactoryAutobuySettings[] $autobuySettings
 */
class Factory extends UnmovableObject implements TaxPayer, canCollectObjects
{
        
    /**
     * Строится
     */
    const STATUS_UNBUILDED = -1;

    /**
     * Строительство остановлено
     */
    const STATUS_BUILD_STOPPED = -2;

    /**
     * Состояние не определено
     */
    const STATUS_UNDEFINED = 0;

    /**
     * Работает
     */
    const STATUS_ACTIVE = 1;

    /**
     * Работа остановлена штатно
     */
    const STATUS_STOPPED = 2;

    /**
     * Работа остановлена автоматически -- на складе нет ресурсов для работы
     */
    const STATUS_NOT_ENOUGHT_RESURSES = 3;

    /**
     * Работа остановленна автоматически
     */
    const STATUS_AUTOSTOPPED = 4;

    /**
     * Работа остановлена автоматически -- недостаточно рабочих
     */
    const STATUS_NOT_ENOUGHT_WORKERS = 5;

    /**
     * Работа остановлена автоматически -- нет необходимой лицензии
     */
    const STATUS_HAVE_NOT_LICENSE = 6;

    public function getUnnpType()
    {
        return Unnp::TYPE_FACTORY;
    }
    
    public function isGoverment($stateId)
    {
        return false;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['proto_id', 'builded', 'name'], 'required'],
            [['proto_id', 'builded', 'holding_id', 'region_id', 'status', 'size', 'manager_uid'], 'integer'],
            [['eff_region', 'eff_workers'], 'number'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'proto_id'    => 'Type ID',
            'builded'    => 'Builded',
            'holding_id' => 'Holding ID',
            'region_id'  => 'Region ID',
            'status'     => 'Статус работы',
            'name'       => 'Name',
            'size'       => 'Size',
            'manager_uid'=> 'Manager Uid',
        ];
    }

    public function getProto()
    {
        return $this->hasOne('app\models\factories\proto\FactoryProto', array('id' => 'proto_id'));
    }

    public function getHolding()
    {
        return $this->hasOne('app\models\Holding', array('id' => 'holding_id'));
    }

    public function getRegion()
    {
        return $this->hasOne('app\models\Region', array('id' => 'region_id'));
    }

    public function getManager()
    {
        return $this->hasOne('app\models\User', array('id' => 'manager_uid'));
    }
    
    public function getAutobuySettings()
    {
        return $this->hasMany('app\models\factories\FactoryAutobuySettings', array('factory_id' => 'id'));
    }
    
    public function getWorkers()
    {
        return $this->hasMany('app\models\Population', array('factory_id' => 'id'));
    }
    
    public function getWorkersCount()
    {
        return intval($this->hasMany('app\models\Population', array('factory_id' => 'id'))->sum("count"));
    }
    
    public function getSalaries()
    {
        return $this->hasMany('app\models\factories\FactoryWorkersSalary', array('factory_id' => 'id'));
    }
    /**
     * 
     * @param integer $limit
     * @return Dealing[]
     */
    public function getDealings($limit)
    {
        return Dealing::findByUnnp($this->unnp, $limit);
    }
    
    public function getVacansies()
    {
        return $this->hasMany('app\models\Vacansy', array('factory_id' => 'id'));
    }

    public function getVacansiesWithSalaryAndCount()
    {
        return $this->hasMany('app\models\Vacansy', array('factory_id' => 'id'))->where('salary > 0 AND count_need > 0')->orderBy("salary DESC");
    }

    public function getVacansiesWithSalary()
    {
        return $this->hasMany('app\models\Vacansy', array('factory_id' => 'id'))->where('salary > 0')->orderBy("salary DESC");
    }
    
    public function getResurseCosts()
    {
        $costs = [];
        foreach ($this->content as $resurse) {
            foreach ($resurse->costs as $resCost) {
                $costs[] = $resCost;
            }
        }
        return $costs;
//        return $this->hasMany(ResurseCost::className(), array('resurse_id' => 'id'))
//                ->viaTable('resurses',['id' => 'IAmPlace']);
    }
    
    public function getStatusName()
    {
        $names = [
            -2 => '<span class="status-error"><i class="icon-stop"></i> Строительство прекращено</span>',
            -1 => '<span class="status-info"><i class="icon-spinner"></i> Идёт строительство</span>',
            0 => '<span class="status-pending"><i class="icon-question-sign"></i> Неизвестен</span>',
            1 => '<span class="status-success"><i class="icon-play"></i> Работает</span>',
            2 => '<span class="status-error"><i class="icon-stop"></i> Работа остановлена</span>',
            3 => '<span class="status-error"><i class="icon-warning-sign"></i> Работа остановлена по причине нехватки ресурсов</span>',
            4 => '<span class="status-error"><i class="icon-pause"></i> Работа остановлена автоматически</span>',
            5 => '<span class="status-error"><i class="icon-warning-sign"></i> Работа остановлена по причине нехватки работников</span>',
            6 => '<span class="status-error"><i class="icon-warning-sign"></i> Работа остановлена по причине отстутствия необходимой лицензии</span>'
        ];
        
        return $names[$this->status];
    }
    
    public function kitSize($resurse_proto_id)
    {
        $kit = FactoryProtoKit::find()->where([
            'resurse_proto_id' => $resurse_proto_id,
            'factory_proto_id' => $this->proto_id
        ])->one();
        if ($kit) {
            return $this->size*$kit->count;
        } else {
            return 0;
        }
    }

    public function storageSize($resurse_proto_id)
    {
        return $this->kitSize($resurse_proto_id)*24;
    }
    
    /**
     * 
     * @param integer $proto_id
     * @param integer $quality
     * @return Resurse
     */
    public function getStorage($proto_id, $quality = 10)
    {
        return Resurse::findOrCreate([
                    'place_id' => $this->IAmPlace,
                    'proto_id' => $proto_id,
                    'quality' => $quality
                ],true);
    }
    
    /**
     * 
     * @param integer $proto_id
     * @return Resurse[]
     */
    public function getStorages($proto_id)
    {
        return Resurse::find()->where([
                    'place_id' => $this->IAmPlace,
                    'proto_id' => $proto_id
                ])->orderBy('quality DESC')->all();
    }
    
    public function pushToStorage($proto_id, $count, $quality = 10) 
    {
        $store = Resurse::findOrCreate([
            'place_id' => $this->IAmPlace,
            'proto_id' => $proto_id,
            'quality' => $quality
        ], false, [
            'count' => 0
        ]);
        if ($store->count+$count <= $this->storageSize($proto_id)) {
            $store->count += $count;
            return $store->save();
        } else {
            return false;
        }
    }
    
    public function delFromStorage($proto_id, $count, $quality = 10) 
    {
        $store = Resurse::findOrCreate([
            'place_id' => $this->IAmPlace,
            'proto_id' => $proto_id,
            'quality' => $quality
        ], false, [
            'count' => 0
        ]);
        if ($store->count >= $count) {
            $store->count -= $count;
            if ($store->count === 0) {
                return $store->delete();
            } else {
                return $store->save();
            }
        } else {
            return false;
        }
    }

    /**
     * 
     * @return \yii\db\ActiveQuery
     */
    public static function findPowerplants()
    {
        $types = FactoryProto::find()->select(['id'])->where(['level'=>FactoryProto::LEVEL_POWERPLANT])->column();
        return Factory::find()->where('proto_id IN ('.implode(',',$types).')');
    }
    
    /**
     * 
     * @return \yii\db\ActiveQuery
     */
    public static function findNoPowerplants()
    {
        $types = FactoryProto::find()->select(['id'])->where(['level'=>FactoryProto::LEVEL_POWERPLANT])->column();
        return Factory::find()->where('proto_id NOT IN ('.implode(',',$types).')');
    }
    
    /**
     * Эффективность работы от числа работников
     * @return double
     */
    public function calcWorkersEff()
    {
        $this->eff_workers = MyMathHelper::myHalfExpo($this->workersCount/($this->proto->sumNeedWorkers*$this->size));
    }
    
    /**
     * Эффективность работы от региона
     * @return double
     */
    public function calcRegionEff()
    {
        $this->eff_region = $this->proto->getRegionEff($this->region);
    }

    public function calcEff()
    {
        $this->calcWorkersEff();
        $this->calcRegionEff();
    }
    
    public function autobuy()
    {
        if (count($this->autobuySettings)) {
            
            foreach ($this->autobuySettings as $settings) {
                
                $costs = ResurseCost::find()
                        ->join('LEFT JOIN', 'resurses', 'resurses.id = resurse_costs.resurse_id')
                        ->where(["resurses.proto_id"=>$settings->resurse_proto_id])
                        ->andWhere([">","resurses.count",0])
                        ->andWhere([">=","resurses.quality",$settings->min_quality])
                        ->andWhere(["<=","cost",$settings->max_cost])
                        ->andWhere(['or',['holding_id'=>null],['holding_id'=>$this->holding_id]])
                        ->andWhere(['or',['state_id'=>null],['state_id'=>$this->getLocatedStateId()]])
                        ->with('resurse')
                        ->orderBy('cost ASC, resurses.quality DESC')
                        ->groupBy('resurses.place_id')
                        ->all();
                $toBuyLeft = $settings->count;
                
                foreach ($costs as $cost) {
                    /* @var $cost ResurseCost */
                    
                    if ($settings->state_id && $cost->resurse->place->getLocatedStateId() !== $settings->state_id) {
                        continue;
                    }
                    if ($settings->holding_id && ($cost->resurse->place->getPlaceType() !== Place::TYPE_FACTORY || $cost->resurse->place->holding_id !== $settings->state_id)) {
                        continue;
                    }

                    $toBuy = min([$toBuyLeft,$cost->resurse->count]);                
                    $sum = $toBuy * $cost->cost;        
                    $transferCost = round($cost->resurse->place->region->calcDist($this->region)*Region::TRANSFER_COST);
                    $sum += $transferCost;  

                    if ($sum > $this->getBalance()) {
                        continue;
                    }
                    
                    $dealing = new Dealing([
                        'proto_id' => 4,
                        'from_unnp' => $cost->resurse->place->unnp,
                        'to_unnp' => $this->unnp,
                        'sum' => -1*$sum,
                        'items' => json_encode([[
                            'type' => 'resurse',
                            'count' => $toBuy,
                            'quality' => $cost->resurse->quality,
                            'proto_id' => $cost->resurse->proto_id
                        ]])
                    ]);
                    $dealing->accept();
                    
                    $toBuyLeft -= $toBuy;
                    if ($toBuyLeft <= 0) {
                        break;
                    }
                }
                        
            }
        }
    }
    
    public function work()
    {
        $countResUsedForWork = 0;
        $sumQualityResUsedForWork = 0;

        // тут проверка на наличие ресурсов для производства            
        foreach ($this->proto->import as $kit) {
            $count = floor($kit->count * $this->size);                
            $storages = $this->getStorages($kit->resurse_proto_id);

            $sum = 0;
            foreach ($storages as $store) {
                $sum += $store->count;
            }

            if ($sum < $count) {
                // ресурса недостаточно
                $this->status = static::STATUS_NOT_ENOUGHT_RESURSES;
                $this->save();
                return;
            }
        }
        $this->status = static::STATUS_ACTIVE;
        $this->save();
        // Теперь точно ресурсов хватает            
        foreach ($this->proto->import as $kit) {
            $count = floor($kit->count * $this->size);                
            $storages = $this->getStorages($kit->resurse_proto_id);

            $deleted = 0;
            foreach ($storages as $store) {
                $del = min([$store->count,$count-$deleted]);
                $this->delFromStorage($kit->resurse_proto_id, $del, $store->quality);

                $deleted += $del;                    
                $sumQualityResUsedForWork += $del*$store->quality;
                if ($deleted === $count)  {
                    break;
                }
            }
            $countResUsedForWork += $count;
        }

        $quality = ($countResUsedForWork) ? round($sumQualityResUsedForWork / $countResUsedForWork) : 10;
        $result = [];
        
        foreach ($this->proto->export as $kit) {
            $count = floor($kit->count * $this->size * $this->eff_workers * $this->eff_region);

            $this->pushToStorage($kit->resurse_proto_id, $count, $quality);
            $result[$kit->resurse_proto_id] = $count;
        }
        
        return $result;
    }
    
    private $_unnp;
    public function getUnnp() {
        if (is_null($this->_unnp)) {
            $u = Unnp::findOneOrCreate(['p_id' => $this->id, 'type' => $this->getUnnpType()]);
            $this->_unnp = ($u) ? $u->id : 0;
        } 
        return $this->_unnp;
    }

    public function changeBalance($delta)
    {
        $this->balance += $delta;
        $this->save();
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function getHtmlName()
    {
        return MyHtmlHelper::a("{$this->proto->name} «{$this->name}»", "load_page('factory-info',{'id':{$this->id}})");
    }
    
    public function getNeedWorkersCountByClass($popClassId)
    {
        foreach ($this->proto->workers as $tWorker) {
            if ($tWorker->pop_class_id === $popClassId) {
                return $tWorker->count * $this->size;
            }
        }
        return 0;
    }
    
    public function getWorkersCountByClass($popClassId)
    {
        $sum = 0;
        foreach ($this->workers as $worker) {
            if ($worker->class === $popClassId) {
                $sum += $worker->count;
            }
        }
        return $sum;
    }
    
    public function getSalaryByClass($popClassId)
    {
        foreach ($this->salaries as $salary) {
            if ($salary->pop_class_id === $popClassId) {
                return $salary->salary;
            }
        }
        
        return false;
    }

    public function getTaxStateId() {
        return $this->region ? $this->region->state_id : 0;
    }

    public function isTaxedInState($stateId) {
        if (is_null($this->region)) {
            return false;
        }
        
        return $this->region->state_id === (int)$stateId;
    }

    public function getContent() {
        return $this->hasMany(Resurse::className(), ['place_id' => 'IAmPlace']);
    }

    private $_iAmPlace;
    public function getIAmPlace() {
        if (is_null($this->_iAmPlace)) {
            $u = Place::findOrCreate(['object_id' => $this->id, 'type' => $this->getPlaceType()], true);
            $this->_iAmPlace = ($u) ? $u->id : 0;
        } 
        return $this->_iAmPlace;
    }

    public function getId() {
        return $this->id;
    }

    public function getPlaceType() {
        return Place::TYPE_FACTORY;
    }
    
    public function getLocatedStateId()
    {
        return $this->region->state_id;
    }

    public function getUserControllerId()
    {
        return $this->manager_uid;
    }

    public function isUserController($userId)
    {
        return $this->manager_uid === $userId;
    }

}
