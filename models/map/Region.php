<?php

namespace app\models\map;

use Yii;
use app\models\base\ActiveRecord;

/**
 * This is the model class for table "regions".
 *
 * @property integer $id
 * @property integer $stateId
 * @property integer $cityId
 * @property string $name
 * @property string $nameShort
 * @property string $flag
 * @property string $anthem
 * @property integer $population
 * @property integer $usersCount
 * @property integer $usersFame
 * @property integer $dateCreated
 * @property integer $dateDeleted
 * @property integer $implodedTo
 * @property array $polygon
 * @property integer $utr
 *
 * @property City[] $cities
 * @property City $city
 * @property Region $implodedToObject
 * @property State $state
 * @property Tile[] $tiles
 */
class Region extends ActiveRecord
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
            [['stateId', 'cityId', 'population', 'usersCount', 'usersFame', 'dateCreated', 'dateDeleted', 'implodedTo', 'utr'], 'default', 'value' => null],
            [['stateId', 'cityId', 'population', 'usersCount', 'usersFame', 'dateCreated', 'dateDeleted', 'implodedTo', 'utr'], 'integer'],
            [['name', 'nameShort'], 'required'],
            [['polygon'], 'string'],
            [['name', 'flag', 'anthem'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 10],
            [['utr'], 'unique'],
            [['cityId'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::className(), 'targetAttribute' => ['cityId' => 'id']],
            [['implodedTo'], 'exist', 'skipOnError' => true, 'targetClass' => Region::className(), 'targetAttribute' => ['implodedTo' => 'id']],
            [['stateId'], 'exist', 'skipOnError' => true, 'targetClass' => States::className(), 'targetAttribute' => ['stateId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stateId' => 'State ID',
            'cityId' => 'City ID',
            'name' => 'Name',
            'nameShort' => 'Name Short',
            'flag' => 'Flag',
            'anthem' => 'Anthem',
            'population' => 'Population',
            'usersCount' => 'Users Count',
            'usersFame' => 'Users Fame',
            'dateCreated' => 'Date Created',
            'dateDeleted' => 'Date Deleted',
            'implodedTo' => 'Imploded To',
            'polygon' => 'Polygon',
            'utr' => 'Utr',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['regionId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'cityId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImplodedToObject()
    {
        return $this->hasOne(Region::className(), ['id' => 'implodedTo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'stateId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTiles()
    {
        return $this->hasMany(Tile::className(), ['regionId' => 'id']);
    }

}
