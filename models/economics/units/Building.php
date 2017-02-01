<?php

namespace app\models\economics\units;

use Yii,
    yii\behaviors\TimestampBehavior,
    app\models\economics\Utr,
    app\models\economics\UtrType,
    app\models\User,
    app\models\Tile,
    app\models\politics\Region,
    app\models\politics\City;

/**
 * This is the model class for table "buildings".
 *
 * @property integer $id
 * @property integer $protoId
 * @property integer $masterId
 * @property integer $tileId
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
 * @property BuildingProto $proto
 * @property Status $status
 * @property TaxPayer $master
 * @property Tile $tile
 * @property City $city
 * @property Region $region
 * @property User $manager
 * 
 */
class Building extends BaseUnit
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
        return 'buildings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['protoId', 'tileId', 'name', 'nameShort', 'size'], 'required'],
            [['protoId', 'masterId', 'tileId', 'size', 'dateCreated', 'dateBuilded', 'dateDeleted', 'managerId', 'statusId', 'taskId', 'taskSubId', 'utr'], 'integer', 'min' => 0],
            [['deterioration', 'efficiencyWorkersCount', 'efficiencyWorkersСontentment', 'efficiencyEquipmentCount', 'efficiencyEquipmentQuality', 'efficiencyBuildingDeterioration', 'efficiencyTile', 'efficiencyCompany', 'taskFactor'], 'number', 'min' => 0, 'max' => 1],
            [['name'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 6],
            [['utr'], 'unique'],
            [['utr'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['utr' => 'id']],
            [['managerId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['managerId' => 'id']],
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
            'statusId' => Yii::t('app', 'Status'),
            'taskId' => Yii::t('app', 'Task ID'),
            'taskSubId' => Yii::t('app', 'Task Sub ID'),
            'taskFactor' => Yii::t('app', 'Task Factor'),
            'utr' => Yii::t('app', 'Utr'),
        ];
    }
    
    public function getProto()
    {
        return BuildingProto::instantiate($this->protoId);
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
    
    public function getManager()
    {
        return $this->hasOne(User::className(), ['id' => 'managerId']);
    }

    public function getTaxStateId(): int
    {
        return $this->tile && $this->tile->region ? $this->tile->region->stateId : 0;
    }

    public function getUserControllerId(): int
    {
        return $this->managerId;
    }

    public function getUtrType(): int
    {
        return UtrType::BUILDING;
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
