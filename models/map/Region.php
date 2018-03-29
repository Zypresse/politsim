<?php

namespace app\models\map;

use Yii;
use app\models\base\ActiveRecord;
use app\models\base\interfaces\MapObject;
use app\models\base\taxpayers\TaxPayerInterface;
use app\models\economy\UtrType;

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
 * @property integer $area
 * @property integer $usersCount
 * @property integer $usersFame
 * @property integer $dateCreated
 * @property integer $dateDeleted
 * @property integer $implodedTo
 * @property integer $utr
 *
 * @property City[] $cities
 * @property City $city
 * @property Region $implodedToObject
 * @property State $state
 * @property Tile[] $tiles
 * @property Polygon $polygon
 */
class Region extends ActiveRecord implements MapObject, TaxPayerInterface
{

    use \app\models\base\taxpayers\TaxPayerTrait;
    
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
            [['stateId', 'cityId', 'population', 'area', 'usersCount', 'usersFame', 'dateCreated', 'dateDeleted', 'implodedTo', 'utr'], 'default', 'value' => null],
            [['stateId', 'cityId', 'population', 'area', 'usersCount', 'usersFame', 'dateCreated', 'dateDeleted', 'implodedTo', 'utr'], 'integer'],
            [['name', 'nameShort'], 'required'],
            [['name', 'flag', 'anthem'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 10],
            [['utr'], 'unique'],
            [['cityId'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['cityId' => 'id']],
            [['implodedTo'], 'exist', 'skipOnError' => true, 'targetClass' => Region::className(), 'targetAttribute' => ['implodedTo' => 'id']],
//            [['stateId'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['stateId' => 'id']],
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
            'area' => 'Area',
            'usersCount' => 'Users Count',
            'usersFame' => 'Users Fame',
            'dateCreated' => 'Date Created',
            'dateDeleted' => 'Date Deleted',
            'implodedTo' => 'Imploded To',
            'utr' => 'Utr',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::class, ['regionId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'cityId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImplodedToObject()
    {
        return $this->hasOne(Region::class, ['id' => 'implodedTo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::class, ['id' => 'stateId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTiles()
    {
        return $this->hasMany(Tile::class, ['regionId' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPolygon()
    {
        return $this->hasOne(Polygon::class, ['ownerId' => 'id'])->andWhere(['ownerType' => Polygon::TYPE_REGION]);
    }

    public function getTaxStateId(): int
    {
        return $this->stateId;
    }

    public function getUserControllerId()
    {
        return null;
    }

    public function getUtrType(): int
    {
        return UtrType::REGION;
    }

    public function isGovernment(int $stateId): bool
    {
        return $this->stateId === $stateId;
    }

    public function isTaxedInState(int $stateId): bool
    {
        return $this->stateId === $stateId;
    }

    public function isUserController(int $userId): bool
    {
        return false;
    }

}
