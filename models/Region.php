<?php

namespace app\models;

use app\components\MyModel;

/**
 * This is the model class for table "regions".
 *
 * @property integer $id
 * @property string $code Код региона
 * @property integer $state_id IS государства
 * @property string $name Название региона
 * @property string $city Название города
 * @property string $b Через запятую — коды соседних регионов
 * @property double $lat Широта центра
 * @property double $lng Долгота центра
 * @property double $separate_risk Риск восстаний (0-1)
 * @property integer $population Население
 * @property double $oil Эффективность добычи нефти (0-1)
 * @property double $natural_gas Эффективность добычи газа (0-1)
 * @property double $coal Эффективность добычи угля (0-1)
 * @property double $nf_ores Эффективность добычи руд цвет. металов (0-1)
 * @property double $f_ores Эффективность добычи руд железа (0-1)
 * @property double $re_ores Эффективность добычи руд редкозем. металов (0-1)
 * @property double $u_ores Эффективность добычи урановой руды (0-1)
 * @property double $wood Эффективность добычи древесины (0-1)
 * @property double $corn Эффективность выращивания зерновых (0-1)
 * @property double $fruits Эффективность выращивания фруктов и овощей (0-1)
 * @property double $fish Эффективность вылова рыбы и морепродуктов (0-1)
 * @property double $meat Эффективность производства мяса и молока (0-1)
 * @property double $wool Эффективность производства шерсти и кожи (0-1)
 * @property double $b_materials Эффективность добычи добываемых стройматериалов (0-1)
 * 
 * @property \app\models\State $state Государство
 * @property \app\models\Population[] $populationGroups Группы населения
 * @property \app\models\CoreCountry[] $cores "Щитки"
 * @property Holding[] $holdings Компании
 * @property Factory[] $factories Фабрики
 * @property Vacansy[] $vacansies Вакансии
 */
class Region extends MyModel
{

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
            [['code', 'name', 'city', /* 'b', */ 'lat', 'lng', 'natural_gas', 'coal', 'nf_ores', 'f_ores', 're_ores', 'u_ores', 'wood', 'corn', 'fruits', 'fish', 'meat', 'wool', 'b_materials'], 'required'],
            [['state_id', 'population'], 'integer'],
            [['lat', 'lng', 'separate_risk', 'oil', 'natural_gas', 'coal', 'nf_ores', 'f_ores', 're_ores', 'u_ores', 'wood', 'corn', 'fruits', 'fish', 'meat', 'wool', 'b_materials'], 'number'],
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

    public function getState()
    {
        return $this->hasOne('app\models\State', array('id' => 'state_id'));
    }

    public function getPopulationGroups()
    {
        return $this->hasMany('app\models\Population', array('region_id' => 'id'));
    }

    public function getHoldings()
    {
        return $this->hasMany('app\models\Holding', array('region_id' => 'id'));
    }

    public function getFactories()
    {
        return $this->hasMany('app\models\Factory', array('region_id' => 'id'));
    }

    public function getVacansies()
    {
        return $this->hasMany('app\models\Vacansy', array('region_id' => 'id'))->orderBy("salary DESC");
    }

    /**
     * Является ли столицей государства
     * @return boolean
     */
    public function isCapital()
    {
        return ($this->state && $this->state->capital === $this->code);
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
}
