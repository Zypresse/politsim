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

}
