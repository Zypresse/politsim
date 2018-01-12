<?php

namespace app\models\economics\units;

use Yii,
    yii\behaviors\TimestampBehavior,
    app\models\economics\Utr,
    app\models\economics\UtrType,
    app\models\economics\TaxPayer,
    app\models\Tile,
    app\models\User,
    app\models\politics\City,
    app\models\politics\Region;

/**
 * This is the model class for table "buildingsTwotiled".
 *
 * @property integer $id
 * @property integer $protoId
 * @property integer $masterId
 * @property integer $tileId
 * @property integer $tile2Id
 * @property string $name
 * @property string $nameShort
 * @property integer $size
 * @property double $deterioration
 * @property double $efficiencyWorkersCount
 * @property double $efficiencyWorkersСontentment
 * @property double $efficiencyEquipmentCount
 * @property double $efficiencyEquipmentQuality
 * @property double $efficiencyBuildingDeterioration
 * @property double $efficiencyTile
 * @property double $efficiencyCompany
 * @property integer $dateCreated
 * @property integer $dateBuilded
 * @property integer $dateDeleted
 * @property integer $managerId
 * @property integer $statusId
 * @property integer $taskId
 * @property integer $taskSubId
 * @property double $taskFactor
 * @property integer $utr
 * 
 * @property BuildingTwotiledProto $proto
 * @property Status $status
 * @property TaxPayer $master
 * @property Tile $tile
 * @property Tile $tile2
 * @property City $city
 * @property City $city2
 * @property Region $region
 * @property Region $region2
 * @property User $manager
 */
class BuildingTwotiled extends BaseUnit
{
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'dateCreated',
                'updatedAtAttribute' => false,
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'buildingsTwotiled';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['protoId', 'tileId', 'tile2Id', 'name', 'nameShort', 'size'], 'required'],
            [['protoId', 'masterId', 'tileId', 'tile2Id', 'size', 'dateCreated', 'dateBuilded', 'dateDeleted', 'managerId', 'statusId', 'taskId', 'taskSubId', 'utr'], 'integer', 'min' => 0],
            [['deterioration', 'efficiencyWorkersCount', 'efficiencyWorkersСontentment', 'efficiencyEquipmentCount', 'efficiencyEquipmentQuality', 'efficiencyBuildingDeterioration', 'efficiencyTile', 'efficiencyCompany'], 'number', 'min' => 0],
            [['taskFactor'], 'number', 'min' => 0, 'max' => 1],
            [['name'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 6],
            [['utr'], 'unique'],
            [['utr'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['utr' => 'id']],
            [['managerId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['managerId' => 'id']],
            [['tile2Id'], 'exist', 'skipOnError' => true, 'targetClass' => Tile::className(), 'targetAttribute' => ['tile2Id' => 'id']],
            [['tileId'], 'exist', 'skipOnError' => true, 'targetClass' => Tile::className(), 'targetAttribute' => ['tileId' => 'id']],
            [['masterId'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['masterId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'protoId' => Yii::t('app', 'Proto ID'),
            'masterId' => Yii::t('app', 'Master ID'),
            'tileId' => Yii::t('app', 'Tile ID'),
            'tile2Id' => Yii::t('app', 'Tile2 ID'),
            'name' => Yii::t('app', 'Name'),
            'nameShort' => Yii::t('app', 'Name Short'),
            'size' => Yii::t('app', 'Size'),
            'deterioration' => Yii::t('app', 'Deterioration'),
            'efficiencyWorkersCount' => Yii::t('app', 'Efficiency Workers Count'),
            'efficiencyWorkersСontentment' => Yii::t('app', 'Efficiency Workers�ontentment'),
            'efficiencyEquipmentCount' => Yii::t('app', 'Efficiency Equipment Count'),
            'efficiencyEquipmentQuality' => Yii::t('app', 'Efficiency Equipment Quality'),
            'efficiencyBuildingDeterioration' => Yii::t('app', 'Efficiency Building Deterioration'),
            'efficiencyTile' => Yii::t('app', 'Efficiency Tile'),
            'efficiencyCompany' => Yii::t('app', 'Efficiency Company'),
            'dateCreated' => Yii::t('app', 'Date Created'),
            'dateBuilded' => Yii::t('app', 'Date Builded'),
            'dateDeleted' => Yii::t('app', 'Date Deleted'),
            'managerId' => Yii::t('app', 'Manager ID'),
            'statusId' => Yii::t('app', 'Status ID'),
            'taskId' => Yii::t('app', 'Task ID'),
            'taskSubId' => Yii::t('app', 'Task Sub ID'),
            'taskFactor' => Yii::t('app', 'Task Factor'),
            'utr' => Yii::t('app', 'Utr'),
        ];
    }

    public function getProto()
    {
        return BuildingTwotiledProto::instantiate($this->protoId);
    }
    
    public function getStatus()
    {
        return Status::findOne($this->statusId);
    }
    
    public function getMasterUtr()
    {
        return $this->hasOne(Utr::className(), ['id' => 'masterId']);
    }
    
    public function getMaster()
    {
        return $this->masterUtr->object;
    }
    
    public function getTile()
    {
        return $this->hasOne(Tile::className(), ['id' => 'tileId']);
    }
    
    public function getTile2()
    {
        return $this->hasOne(Tile::className(), ['id' => 'tile2Id']);
    }
    
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'regionId'])
                ->via('tile');
    }
    
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'cityId'])
                ->via('tile');
    }
    
    public function getRegion2()
    {
        return $this->hasOne(Region::className(), ['id' => 'regionId'])
                ->via('tile2');
    }
    
    public function getCity2()
    {
        return $this->hasOne(City::className(), ['id' => 'cityId'])
                ->via('tile2');
    }
    
    public function getManager()
    {
        return $this->hasOne(User::className(), ['id' => 'managerId']);
    }
    
    public function getTaxStateId(): int
    {
        return $this->region ? $this->region->stateId : 0;
    }

    public function getUserControllerId()
    {
        return (int)$this->managerId;
    }

    public function getUtrType(): int
    {
        return UtrType::BUILDINGTWOTILED;
    }

    public function isGoverment(int $stateId): bool
    {
        return false;
    }

    public function isTaxedInState(int $stateId): bool
    {
        return $this->getTaxStateId() === $stateId;
    }

    public function isUserController(int $userId): bool
    {
        return (int)$this->managerId === $userId;
    }

}
