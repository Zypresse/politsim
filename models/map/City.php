<?php

namespace app\models\map;

use Yii;
use app\models\base\ActiveRecord;
use app\models\base\interfaces\MapObject;
use app\models\base\taxpayers\TaxPayerInterface;
use app\models\economy\UtrType;

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
 * @property integer $utr
 *
 * @property Region $region
 * @property Tile[] $tiles
 * @property Polygon $polygon
 */
class City extends ActiveRecord implements MapObject, TaxPayerInterface
{

    use \app\models\base\taxpayers\TaxPayerTrait;
    
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPolygon()
    {
        return $this->hasOne(Polygon::class, ['ownerId' => 'id'])->andWhere(['ownerType' => Polygon::TYPE_CITY]);
    }

    public function getTaxStateId(): int
    {
        return $this->region ? $this->region->stateId : 0;
    }

    public function getUserControllerId()
    {
        return null;
    }

    public function getUtrType(): int
    {
        return UtrType::CITY;
    }

    public function isGovernment(int $stateId): bool
    {
        return $this->region ? $this->region->stateId === $stateId : false;
    }

    public function isTaxedInState(int $stateId): bool
    {
        return $this->region ? $this->region->stateId === $stateId : false;
    }

    public function isUserController(int $userId): bool
    {
        return false;
    }

}
