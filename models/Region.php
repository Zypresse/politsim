<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "regions".
 *
 * @property integer $id
 * @property string $code
 * @property integer $state_id
 * @property string $name
 * @property string $city
 * @property string $b
 * @property double $lat
 * @property double $lng
 * @property double $separate_risk
 * @property integer $population
 * @property double $oil
 * @property double $natural_gas
 * @property double $coal
 * @property double $nf_ores
 * @property double $f_ores
 * @property double $re_ores
 * @property double $u_ores
 * @property double $wood
 * @property double $corn
 * @property double $fruits
 * @property double $fish
 * @property double $meat
 * @property double $wool
 * @property double $b_materials
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
            [['code', 'name', 'city', /*'b', */'lat', 'lng', 'natural_gas', 'coal', 'nf_ores', 'f_ores', 're_ores', 'u_ores', 'wood', 'corn', 'fruits', 'fish', 'meat', 'wool', 'b_materials'], 'required'],
            [['state_id', 'population'], 'integer'],
            [['lat', 'lng', 'separate_risk', 'oil', 'natural_gas', 'coal', 'nf_ores', 'f_ores', 're_ores', 'u_ores', 'wood', 'corn', 'fruits', 'fish', 'meat', 'wool', 'b_materials'], 'number'],
            [['code'], 'string', 'max' => 7],
            [['name', 'city'], 'string', 'max' => 300],
            //[['b'], 'string', 'max' => 2555],
            [['b'],'default','value'=>''],
            [['code'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'state_id' => 'State ID',
            'name' => 'Name',
            'city' => 'City',
            'b' => 'B',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'separate_risk' => 'Separate Risk',
            'population' => 'Population',
            'oil' => 'Oil',
            'natural_gas' => 'Natural Gas',
            'coal' => 'Coal',
            'nf_ores' => 'Nf Ores',
            'f_ores' => 'F Ores',
            're_ores' => 'Re Ores',
            'u_ores' => 'U Ores',
            'wood' => 'Wood',
            'corn' => 'Corn',
            'fruits' => 'Fruits',
            'fish' => 'Fish',
            'meat' => 'Meat',
            'wool' => 'Wool',
            'b_materials' => 'B Materials',
        ];
    }

    public static function findByCode($code)
    {
        return static::find()->where(["code"=>$code])->one();
    }

    public function getBorders()
    {
        $b = [];
        if ($this->b) {
            $models = Region::find(['condition'=>'code IN ('.implode(",", $this->b).')'])->all();
            foreach ($models as $model) {
                $b[$model->code] = $model->name;
            }
        }

        return $b;
    }

    public function beforeSave($insert)
    {
        
        if (is_array($this->b)) {
        
            $this->b = implode(',', $this->b);
        }
        return parent::beforeSave($insert);
    }
}
