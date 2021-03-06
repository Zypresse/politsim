<?php

namespace app\models\economics;

use Yii,
    yii\behaviors\TimestampBehavior,
    app\models\economics\TaxPayerModel,
    app\models\User,
    app\models\politics\State,
    app\models\economics\units\Building,
    app\models\economics\units\BuildingTwotiled,
    app\models\economics\units\Unit,
    app\models\economics\resources\Currency;

/**
 * Акционерные общества
 *
 * @property integer $id
 * @property integer $stateId
 * @property integer $mainOfficeId
 * @property integer $directorId
 * @property string $name
 * @property string $nameShort
 * @property string $flag
 * @property double $efficiencyManagement
 * @property double $capitalization
 * @property double $sharesPrice
 * @property integer $sharesIssued
 * @property boolean $isGoverment
 * @property boolean $isHalfGoverment
 * @property integer $dateCreated
 * @property integer $dateDeleted
 * @property integer $utr
 * 
 * @property State $state
 * @property Building $mainOffice
 * @property BaseUnit[] $objects
 * @property Building[] $buildings
 * @property BuildingTwotiled[] $buildingsTwotiled
 * @property Unit[] $units
 * @property User $director
 * @property Resource[] $shares
 * @property License[] $licenses
 * @property License[] $licensesRequested
 * @property License[] $licensesExpired
 * @property CompanyDecision[] $decisions
 * @property CompanyDecision[] $decisionsActive
 * @property CompanyDecision[] $decisionsArсhived
 * @property Currency[] $currencies
 * 
 */
class Company extends TaxPayerModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'companies';
    }

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
    public function rules()
    {
        return [
            [['name', 'nameShort', 'sharesIssued', 'sharesPrice'], 'required'],
            [['stateId', 'mainOfficeId', 'directorId', 'sharesIssued', 'dateCreated', 'dateDeleted', 'utr'], 'integer', 'min' => 0],
            [['efficiencyManagement', 'capitalization', 'sharesPrice'], 'number', 'min' => 0],
            [['name', 'flag'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 6],
            [['isGoverment', 'isHalfGoverment'], 'boolean'],
            [['utr'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['utr' => 'id']],
            [['directorId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['directorId' => 'id']],
//            [['mainOfficeId'], 'exist', 'skipOnError' => true, 'targetClass' => Building::className(), 'targetAttribute' => ['mainOfficeId' => 'id']],
            [['stateId'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['stateId' => 'id']],
            [['flag'], 'validateFlag'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'stateId' => Yii::t('app', 'State ID'),
            'mainOfficeId' => Yii::t('app', 'Main Office ID'),
            'directorId' => Yii::t('app', 'Director ID'),
            'name' => Yii::t('app', 'Name'),
            'nameShort' => Yii::t('app', 'Name Short'),
            'flag' => Yii::t('app', 'Flag'),
            'efficiencyManagement' => Yii::t('app', 'Efficiency Management'),
            'capitalization' => Yii::t('app', 'Capitalization'),
            'sharesPrice' => Yii::t('app', 'Shares Price'),
            'sharesIssued' => Yii::t('app', 'Shares Issued'),
            'dateCreated' => Yii::t('app', 'Date Created'),
            'dateDeleted' => Yii::t('app', 'Date Deleted'),
            'utr' => Yii::t('app', 'Utr'),
        ];
    }
    
    public function getDirector()
    {
        return $this->hasOne(User::className(), ['id' => 'directorId']);
    }
    
    public function getMainOffice()
    {
        return $this->hasOne(Building::className(), ['id' => 'mainOfficeId']);
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'stateId']);
    }

    public function getTaxStateId(): int
    {
        return $this->stateId;
    }

    public function getUserControllerId(): int
    {
        return (int)$this->directorId;
    }

    public function getUtrType(): int
    {
        return UtrType::COMPANY;
    }

    public function isGoverment(int $stateId): bool
    {
        return false;
    }

    public function isTaxedInState(int $stateId): bool
    {
        return (int)$this->stateId === $stateId;
    }

    public function isUserController(int $userId): bool
    {
        return (int)$this->directorId === $userId;
    }
    
    public function delete()
    {
        $this->dateDeleted = time();
        return $this->save();
    }
    
    public function getShares()
    {
        return $this->hasMany(Resource::className(), ['subProtoId' => 'id'])->where(['protoId' => ResourceProto::SHARE]);
    }
    
    public function getLicenses()
    {
        return $this->hasMany(License::className(), ['companyId' => 'id'])
                ->where(['is not', 'dateGranted', null])
                ->andWhere(['>', 'dateExpired', time()])
                ->with('state');
    }
    
    public function isHaveLicense(int $protoId, int $stateId)
    {
        return $this->getLicenses()
                ->andWhere(['protoId' => $protoId, 'stateId' => $stateId])
                ->exists();
    }
    
    public function getLicensesExpired()
    {
        return $this->hasMany(License::className(), ['companyId' => 'id'])
                ->where(['<', 'dateExpired', time()])
                ->with('state');
    }
    
    public function getLicensesRequested()
    {
        return $this->hasMany(License::className(), ['companyId' => 'id'])
                ->where(['dateGranted' => null])
                ->with('state');
    }
    
    public function getDecisions()
    {
        return $this->hasMany(CompanyDecision::className(), ['companyId' => 'id']);
    }
    
    public function getDecisionsActive()
    {
        return $this->getDecisions()
                ->where(['dateFinished' => null])
                ->orderBy(['dateCreated' => SORT_DESC]);
    }
    
    public function getDecisionsArсhived()
    {
        return $this->getDecisions()
                ->where(['is not', 'dateFinished', null])
                ->orderBy(['dateCreated' => SORT_DESC]);
    }
    
    public function getBuildings()
    {
        $this->getUtrForced();
        return $this->hasMany(Building::className(), ['masterId' => 'utr'])
                ->where(['dateDeleted' => null]);
    }
    
    public function getBuildingsTwotiled()
    {
        $this->getUtrForced();
        return $this->hasMany(BuildingTwotiled::className(), ['masterId' => 'utr'])
                ->where(['dateDeleted' => null]);
    }
    
    public function getUnits()
    {
        $this->getUtrForced();
        return $this->hasMany(Unit::className(), ['masterId' => 'utr'])
                ->where(['dateDeleted' => null]);
    }
    
    public function getObjects()
    {
        return array_merge($this->buildings, $this->buildingsTwotiled, $this->units);
    }
    
    public function getCurrencies()
    {
        $this->getUtrForced();
        return $this->hasMany(Currency::className(), ['emissionerId' => 'utr']);
    }
    
    public function updateParams($save = true)
    {
        
        // подсчёт эффективности управления
        // TODO
        
        // Проверка, является ли оно гос. предприятием или предприятием с госучастием
        // TODO проходить по владельцам акций и спрашивать каждого. 
        // если наберется больше 50% в сумме то гос.предприятие. 
        // если больше нуля то с участием
        
        $this->sharesIssued = 0;
        $countGoverment = 0;
        foreach ($this->shares as $share) {
            $this->sharesIssued += $share->count;
            if ($share->master->isGoverment($this->stateId)) {
                $countGoverment += $share->count;
            }
        }
        $this->isGoverment = false;
        $this->isHalfGoverment = false;
        if ($this->sharesIssued > 0) {
            if ($countGoverment/$this->sharesIssued > 0.5) {
                $this->isGoverment = true;
            } elseif ($countGoverment/$this->sharesIssued > 0) {
                $this->isHalfGoverment = true;
            }
        }
        
        // Подсчёт капитализации, пока только формальная стоимость акций
        $this->capitalization = $this->sharesIssued*$this->sharesPrice;
        
        if ($save) {
            $this->save();
        }
    }
    
    
    /**
     * 
     * @param \app\models\User $creator
     * @return boolean
     */
    public function createNew(User $creator)
    {
        $this->directorId = $creator->id;
        
        if ($this->save()) {
        
            $share = new Resource([
                'protoId' => ResourceProto::SHARE,
                'subProtoId' => $this->id,
                'masterId' => $creator->getUtr(),
                'locationId' => $creator->getUtr(),
                'count' => $this->sharesIssued,
            ]);

            if ($share->save()) {
                // TODO деньги на счёт компании
                return true;
            }

            $this->addErrors($share->getErrors());
        }
        
        return false;
    }
    
    public function isShareholder(int $utr)
    {
        foreach ($this->shares as $share) {
            if ((int)$share->masterId === $utr) {
                return true;
            }
        }
        return false;
    }

}
