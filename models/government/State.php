<?php

namespace app\models\government;

use Yii;
use app\models\base\ActiveRecord;
use app\models\map\Region;
use app\models\map\City;
use app\models\map\Polygon;
use app\models\map\Tile;
use app\models\auth\User;
use app\models\base\interfaces\MapObject;
use app\models\base\taxpayers\TaxPayerInterface;
use app\models\economy\UtrType;

/**
 * This is the model class for table "states".
 *
 * @property integer $id
 * @property string $name
 * @property string $nameShort
 * @property string $flag
 * @property string $anthem
 * @property integer $cityId
 * @property string $mapColor
 * @property integer $govermentFormId
 * @property integer $stateStructureId
 * @property integer $population
 * @property integer $area
 * @property integer $usersCount
 * @property integer $usersFame
 * @property integer $dateCreated
 * @property integer $dateDeleted
 * @property integer $utr
 *
 * @property Citizenship[] $citizenships
 * @property User[] $citizens
 * @property ElectoralDistrict[] $electoralDistricts
 * @property Party[] $parties
 * @property Region[] $regions
 * @property City $capital
 * 
 * @property string $tooltipName
 */
class State extends ActiveRecord implements MapObject, TaxPayerInterface
{

    use \app\models\base\taxpayers\TaxPayerTrait;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'states';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'nameShort'], 'required'],
            [['cityId', 'govermentFormId', 'stateStructureId', 'dateCreated', 'dateDeleted', 'utr'], 'default', 'value' => null],
            [['population', 'area', 'usersCount', 'usersFame'], 'default', 'value' => 0],
            [['cityId', 'govermentFormId', 'stateStructureId', 'population', 'area', 'usersCount', 'usersFame', 'dateCreated', 'dateDeleted', 'utr'], 'integer'],
            [['name', 'flag', 'anthem'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 10],
            [['mapColor'], 'string', 'max' => 6],
            [['utr'], 'unique'],
            [['cityId'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['cityId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'nameShort' => 'Name Short',
            'flag' => 'Flag',
            'anthem' => 'Anthem',
            'cityId' => 'City ID',
            'mapColor' => 'Map Color',
            'govermentFormId' => 'Goverment Form ID',
            'stateStructureId' => 'State Structure ID',
            'population' => 'Population',
            'area' => 'Area',
            'usersCount' => 'Users Count',
            'usersFame' => 'Users Fame',
            'dateCreated' => 'Date Created',
            'dateDeleted' => 'Date Deleted',
            'utr' => 'Utr',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCitizenships()
    {
        return $this->hasMany(Citizenship::class, ['stateId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCitizens()
    {
        return $this->hasMany(User::class, ['id' => 'userId'])->viaTable('citizenships', ['stateId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElectoralDistricts()
    {
        return $this->hasMany(ElectoralDistrict::class, ['stateId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParties()
    {
        return $this->hasMany(Party::class, ['stateId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegions()
    {
        return $this->hasMany(Region::class, ['stateId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapital()
    {
        return $this->hasOne(City::class, ['id' => 'cityId']);
    }
    
    
    public function getTaxStateId(): int
    {
        return $this->id;
    }

    public function getUserControllerId()
    {
        return null;
    }

    public function getUtrType(): int
    {
        return UtrType::STATE;
    }

    public function isGovernment(int $stateId): bool
    {
        return $this->id === $stateId;
    }

    public function isTaxedInState(int $stateId): bool
    {
        return $this->id === $stateId;
    }

    public function isUserController(int $userId): bool
    {
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPolygon()
    {
        return $this->hasOne(Polygon::class, ['ownerId' => 'id'])->where(['ownerType' => Polygon::TYPE_STATE]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTiles()
    {
        return Tile::find()->where(['regionId' => $this->getRegions()->select('id')->column()]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findActive()
    {
        return self::find()->andWhere(['dateDeleted' => null]);
    }
    
    /**
     * 
     * @return string
     */
    public function getTooltipName()
    {
        return mb_strlen($this->name) > 18 ? $this->nameShort : $this->name;
    }

}
