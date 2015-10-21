<?php

namespace app\models\factories;

use app\components\MyMathHelper,
    app\components\MyHtmlHelper,
    app\components\NalogPayer,
    app\models\Unnp,
    app\models\factories\proto\FactoryProto,
    app\models\factories\proto\FactoryProtoKit,
    app\models\resurses\Resurse,
    app\models\objects\UnmovableObject;

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
 */
class Factory extends UnmovableObject implements NalogPayer
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

    protected function getUnnpType()
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
    
    public function getStatusName()
    {
        $names = [
            -2 => 'Строительство прекращено',
            -1 => 'Идёт строительство',
            0 => 'Неизвестен',
            1 => 'Работает',
            2 => 'Работа остановлена',
            3 => 'Работа остановлена по причине нехватки ресурсов',
            4 => 'Работа остановлена автоматически',
            5 => 'Работа остановлена по причине нехватки работников',
            6 => 'Работа остановлена по причине отстутствия необходимой лицензии'
        ];
        
        return $names[$this->status];
    }

    public function storageSize($resurse_proto_id)
    {
        $kit = FactoryProtoKit::find()->where([
            'resurse_proto_id' => $resurse_proto_id,
            'factory_proto_id' => $this->proto_id
        ])->one();
        if ($kit) {
            return $this->size*24*$kit->count;
        } else {
            return 0;
        }
    }
    
    public function pushToStorage($proto_id, $count) 
    {
        $store = Resurse::findOrCreate([
            'place_id' => $this->id,
            'proto_id' => $proto_id
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
    
    public function delFromStorage($proto_id, $count) 
    {
        $store = Resurse::findOrCreate([
            'place_id' => $this->id,
            'proto_id' => $proto_id
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
            return false;;
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
    public function getWorkersEff()
    {
        return MyMathHelper::myHalfExpo($this->workersCount/($this->proto->sumNeedWorkers*$this->size));
    }
    
    /**
     * Эффективность работы от региона
     * @return double
     */
    public function getRegionEff()
    {
        return 1;
    }
    
    public function work()
    {
        if ($this->status == static::STATUS_ACTIVE) {
            // var_dump($this->getWorkersEff());
            // тут проверка на наличие ресурсов для производства и их уничтожение
            // Автозакупка электричества
            
            foreach ($this->proto->export as $kit) {
                $count = floor($kit->count * $this->size * $this->getWorkersEff() * $this->getRegionEff());
                
                $this->pushToStorage($kit->resurse_proto_id, $count);
            }
        }
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
        
    }

    public function getBalance()
    {
        return 0;
    }

    public function getHtmlName()
    {
        return MyHtmlHelper::a("{$this->proto->name} «{$this->name}»", "load_page('factory-info',{'id':{$this->id}})");
    }

}
