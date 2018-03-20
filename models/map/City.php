<?php

namespace app\models\map;

use Yii;
use app\models\base\ActiveRecord;

/**
 * This is the model class for table "cities".
 *
 * @property integer $id
 * @property integer $regionId
 * @property string $name
 * @property string $nameShort
 * @property string $flag
 * @property string $anthem
 * @property integer $population
 * @property integer $usersCount
 * @property integer $usersFame
 * @property array $polygon
 * @property integer $utr
 *
 * @property Region $region
 * @property Tile[] $tiles
 */
class City extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['regionId', 'population', 'usersCount', 'usersFame', 'utr'], 'default', 'value' => null],
            [['regionId', 'population', 'usersCount', 'usersFame', 'utr'], 'integer'],
            [['name', 'nameShort'], 'required'],
            [['polygon'], 'safe'],
            [['name', 'flag', 'anthem'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 10],
            [['utr'], 'unique'],
            [['regionId'], 'exist', 'skipOnError' => false, 'targetClass' => Region::className(), 'targetAttribute' => ['regionId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'regionId' => 'Region ID',
            'name' => 'Name',
            'nameShort' => 'Name Short',
            'flag' => 'Flag',
            'anthem' => 'Anthem',
            'population' => 'Population',
            'usersCount' => 'Users Count',
            'usersFame' => 'Users Fame',
            'polygon' => 'Polygon',
            'utr' => 'Utr',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'regionId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTiles()
    {
        return $this->hasMany(Tile::className(), ['cityId' => 'id']);
    }

}
