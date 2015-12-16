<?php

namespace app\models;

use app\components\TaxPayer,
    app\components\MyModel,
    app\components\MyHtmlHelper,
    app\models\Unnp;

/**
 * This is the model class for table "regions".
 *
 * @property integer $id
 * @property string $code Код региона
 * @property integer $state_id IS государства
 * @property string $name Название региона
 * @property string $city Название города
 * @property string $name_default Название региона
 * @property string $city_default Название города
 * @property string $b Через запятую — коды соседних регионов
 * @property double $lat Широта центра
 * @property double $lng Долгота центра
 * @property integer $population Население
 *  
 * @property State $state Государство
 * @property Population[] $populationGroups Группы населения
 * @property Population[] $populationGroupsWithoutFactory Группы населения не работающие на фабриках
 * @property CoreCountry[] $cores "Щитки"
 * @property Holding[] $holdings Компании
 * @property factories\Factory[] $factories Фабрики
 * @property Vacansy[] $vacansies Вакансии
 * @property Vacansy[] $vacansiesWithSalaryAndCount Актуальнаые вакансии
 * @property Vacansy[] $vacansiesWithSalary Потенцальные вакансии
 * @property RegionDiggingEff[] $diggingEffs
 */
class Region extends MyModel implements TaxPayer
{

    public function getUnnpType()
    {
        return Unnp::TYPE_REGION;
    }
    
    public function getStocks()
    {
        return $this->hasMany('app\models\Stock', array('unnp' => 'unnp'));
    }
    
    private $_unnp;
    public function getUnnp() {
        if (is_null($this->_unnp)) {
            $u = Unnp::findOneOrCreate(['p_id' => $this->id, 'type' => $this->getUnnpType()]);
            $this->_unnp = ($u) ? $u->id : 0;
        } 
        return $this->_unnp;
    }

    public function isGoverment($stateId)
    {
        return $this->state_id === $stateId;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'regions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'city', /* 'b', */ 'lat', 'lng'], 'required'],
            [['state_id', 'population'], 'integer'],
            [['lat', 'lng'], 'number'],
            [['code'], 'string', 'max' => 7],
            [['name', 'city'], 'string', 'max' => 300],
            //[['b'], 'string', 'max' => 2555],
            [['b'], 'default', 'value' => ''],
            [['code'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'code'          => 'Code',
            'state_id'      => 'State ID',
            'name'          => 'Name',
            'city'          => 'City',
            'b'             => 'B',
            'lat'           => 'Lat',
            'lng'           => 'Lng',
            'separate_risk' => 'Separate Risk',
            'population'    => 'Population',
            'oil'           => 'Oil',
            'natural_gas'   => 'Natural Gas',
            'coal'          => 'Coal',
            'nf_ores'       => 'Nf Ores',
            'f_ores'        => 'F Ores',
            're_ores'       => 'Re Ores',
            'u_ores'        => 'U Ores',
            'wood'          => 'Wood',
            'corn'          => 'Corn',
            'fruits'        => 'Fruits',
            'fish'          => 'Fish',
            'meat'          => 'Meat',
            'wool'          => 'Wool',
            'b_materials'   => 'B Materials',
        ];
    }

    public function setPublicAttributes()
    {
        return [
            'id',
            'code',
            'state_id',
            'name',
            'city',
            'b',
            'lat',
            'lng',
            'separate_risk',
            'population',
            'oil',
            'natural_gas',
            'coal',
            'nf_ores',
            'f_ores',
            're_ores',
            'u_ores',
            'wood',
            'corn',
            'fruits',
            'fish',
            'meat',
            'wool',
            'b_materials'
        ];
    }

    /**
     * Список пограничных регионов
     * @return Region[]
     */
    /** @TODO wtf delete this shit */
    public function getBorders()
    {
        $b = [];
        if ($this->b) {
            $models = Region::find(['condition' => 'code IN (' . implode(",", $this->b) . ')'])->all();
            foreach ($models as $model) {
                $b[$model->code] = $model->name;
            }
        }

        return $b;
    }

    private $_bordersArray = null;
    /**
     * Список пограничных регионов
     * @return Region[]
     */
    public function getBordersArray()
    {
        if (is_null($this->_bordersArray)) {
            $this->b = explode(",", $this->b);
            if ($this->b) {
                $this->_bordersArray = Region::findBySql('SELECT * FROM '.$this->tableName().' WHERE code IN (\'' . implode("','", $this->b) . '\') ORDER BY state_id')->all();
            } else {
                $this->_bordersArray = [];
            }
        }
        return $this->_bordersArray;
    }
    
    /**
     * Стоимость телепортации ресурсов за км.
     */
    const TRANSFER_COST = 0.1;
    
    /**
     * Дистанция от этого региона до переданного в км.
     * @param Region $to
     */
    public function calcDist($to)
    {
        // магические константы для говнокарты
        return \sqrt(\pow(123*($to->lat - $this->lat), 2)+\pow(86*($to->lng - $this->lng), 2));
    }

    public function getState()
    {
        return $this->hasOne('app\models\State', array('id' => 'state_id'));
    }

    public function getPopulationGroups()
    {
        return $this->hasMany('app\models\Population', array('region_id' => 'id'));
    }

    public function getPopulationGroupsWithoutFactory()
    {
        return $this->hasMany('app\models\Population', array('region_id' => 'id'))->where(['factory_id'=>'0']);
    }

    public function getHoldings()
    {
        return $this->hasMany('app\models\Holding', array('region_id' => 'id'));
    }

    public function getFactories()
    {
        return $this->hasMany('app\models\factories\Factory', array('region_id' => 'id'));
    }

    public function getVacansies()
    {
        return $this->hasMany('app\models\Vacansy', array('region_id' => 'id'))->orderBy("salary DESC");
    }

    public function getVacansiesWithSalaryAndCount()
    {
        return $this->hasMany('app\models\Vacansy', array('region_id' => 'id'))->where('salary > 0 AND count_need > 0')->orderBy("salary DESC");
    }

    public function getVacansiesWithSalary()
    {
        return $this->hasMany('app\models\Vacansy', array('region_id' => 'id'))->where('salary > 0')->orderBy("salary DESC");
    }
    
    public function getDiggingEffs()
    {
        return $this->hasMany('app\models\RegionDiggingEff', array('region_id' => 'id'))->orderBy('group_id');
    }

    public function getDiggingEff($resurse_proto_id)
    {
        return $this->hasOne('app\models\RegionDiggingEff', array('region_id' => 'id'))->where(['resurse_proto_id' => $resurse_proto_id])->one();
    }

    /**
     * Является ли столицей государства
     * @return boolean
     */
    public function isCapital()
    {
        return ($this->state && $this->state->capital === $this->id);
    }

    public function getCores()
    {
        return $this->hasMany('app\models\CoreCountry', ['id' => 'core_id'])
                ->viaTable('cores_regions', ['region_id' => 'id']);
    }
    
    public function afterSave($insert,$changedAttributes)
    {
        // Если изменилось государство
        if (!$insert && isset($changedAttributes["state_id"])) {
            
            $this->name = $this->name_default;
            $this->city = $this->city_default;
            $this->save();
            
            /*
             * У каждого предприятия есть регион и страна (регион при этом может принадлежать другой стране).
             *  При смене владельца региона проходимся по всем его предприятиям и для каждого:
             *  1. Если предприятие государственное:
             *  Оно лишается привязки к региону (или привязывается к столице своего государства) (ликвидировать имхо, жёстко, если оно с утратой региона стало не нужно, его могут ликвидировать владельцы)
             *  2. Если предприятие частное:
             *  Оно сохраняет привязку к региону и проходит провернку на соответствие законам нового владельца
             *  2.1 Если в новом государстве запрещены частные компании то оно национализируется и становится гос. компанией через, допустим, сутки. За это время владельцы могут ликвидировать компанию, чтобы "не отдать его врагу" или переехать.
             *  2.2 Если в новом государстве есть гос. монополия на какие-либо виды деятельности, зарегистрированные в фирме, то она лишается их
             *  2.3 Если в новом государстве разрешены частные компании, но запрещены акционеры-иностранцы, а они есть, то через, допустим, сутки, компания ликвидируется. За это время акционеры могут получить гражданство нового государства или переехать.
             */ 
            /*
            foreach ($this->holdings as $holding) {
                if ($holding->isGosHolding()) {
                    $holding->region_id = 0;//$holding->state->region_id;
                    $holding->save();
                } else {
                    if (!$this->state->allow_register_holdings) {
                        // Становится гос. предприятием
                        foreach ($holding->stocks as $stock) {
                            $stock->remove();
                        }
                        $stock = new Stock([
                            'holding_id' => $holding->id,
                            'post_id' => 0 // Тут надо найти пост министра, которому подойдёт это предприятие
                        ]);
                    }
                    foreach ($this->state->licenses as $license) {
                        if ($license->is_only_goverment && $holding->isHaveLicense($this->state_id,$license->type->id)) {
                            $holding->deleteLicense($license->type->id);
                        }
                    }
                    
                }
            }*/
        }
        
        return parent::afterSave($insert,$changedAttributes);
    }

    public function changeBalance($delta)
    {
        
    }

    public function getBalance()
    {
        return 0;
    }

    public function getCityHtmlName()
    {
        return $this->city." (".$this->name.($this->state?", ".MyHtmlHelper::a($this->state->short_name, "load_page('state-info',{'id':{$this->state_id}})").")":")");
    }
    
    public function getHtmlName()
    {
        return MyHtmlHelper::a($this->name,"show_region({$this->id})").($this->state?" (".$this->state->getHtmlShortName().")":"");
    }

    public function getTaxStateId()
    {
        return $this->state_id;
    }

    public function isTaxedInState($stateId)
    {
        return false;
    }

    public function getUserControllerId()
    {
        return 0;
    }

    public function isUserController($userId)
    {
        return false;
    }

}
