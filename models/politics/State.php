<?php

namespace app\models\politics;

use Yii,
    app\models\economics\UtrType,
    app\models\politics\constitution\Constitution,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\ConstitutionOwnerType,
    app\models\politics\constitution\ConstitutionOwner,
    app\models\politics\constitution\articles\statesonly\Parties,
    app\models\politics\constitution\articles\statesonly\Business,
    app\models\population\Pop,
    app\models\Tile,
    app\models\politics\bills\Bill,
    app\models\politics\elections\ElectoralDistrict,
    app\models\economics\License,
    app\models\economics\Company,
    app\models\economics\TaxPayer,
    app\components\RegionCombiner,
    app\components\MyMathHelper;

/**
 * Государство
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
 * @property integer $usersCount
 * @property integer $usersFame
 * @property integer $dateCreated
 * @property integer $dateDeleted
 * @property integer $utr
 * 
 * @property string $polygon
 * @property boolean $isPartiesCreatingAllowed
 * @property integer $recommendedParliamentSize
 * 
 * @property City $city
 * @property GovermentForm $govermentForm
 * @property StateStructure $stateStructure
 * @property Region[] $regions
 * @property City[] $cities
 * @property Agency[] $agencies
 * @property AgencyPost[] $posts
 * @property AgencyPost $leaderPost
 * @property Party[] $parties
 * @property Party[] $partiesUnconfirmed
 * @property Constitution $constitution
 * @property Tile[] $tiles
 * @property Pop[] $pops
 * @property Bill[] $bills
 * @property Bill[] $billsActive
 * @property Bill[] $billsFinished
 * @property ElectoralDistrict[] $districts
 * @property Company[] $companies
 * @property License[] $licenses
 * @property License[] $licensesUnconfirmed
 * @property LicenseRule[] $licenseRules
 *
 * @author ilya
 */
class State extends ConstitutionOwner
{
    
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
            [['name'], 'string', 'max' => 255],
            [['nameShort', 'mapColor'], 'string', 'max' => 6],
            [['flag', 'anthem'], 'string'],
            [['cityId', 'govermentFormId', 'stateStructureId', 'population', 'usersCount', 'usersFame', 'dateCreated', 'dateDeleted', 'utr'], 'integer', 'min' => 0],
            [['anthem'], 'validateAnthem'],
            [['flag'], 'validateFlag'],
        ];
    }
    
    /**
     * Возвращает константное значение типа налогоплательщика
     * @return integer
     */
    public function getUtrType()
    {
        return UtrType::STATE;
    }
        
    /**
     * Является ли плательщик правительством страны
     * @param integer $stateId
     * @return boolean
     */
    public function isGoverment(int $stateId)
    {
        return (int)$this->id === $stateId;
    }
            
    /**
     * ID страны, в которой он платит налоги
     * @return integer
     */
    public function getTaxStateId()
    {
        return $this->id;
    }
    
    /**
     * Является ли налогоплательщиком государства
     * @param integer $stateId
     * @return boolean
     */
    public function isTaxedInState(int $stateId)
    {
        return (int)$this->id === $stateId;
    }
    
    /**
     * ID юзера, который управляет этим налогоплательщиком
     */
    public function getUserControllerId()
    {
        return false;
    }
    
    /**
     * Может ли юзер управлять этим налогоплательщиком
     * @param integer $userId
     * @return boolean
     */
    public function isUserController(int $userId)
    {
        return false;
    }    
        
    private $_govermentForm = null;
    public function getGovermentForm()
    {
        if (is_null($this->_govermentForm)) {
            $this->_govermentForm = GovermentForm::findOne($this->govermentFormId);
        }
        return $this->_govermentForm;
    }
    
    private $_stateStructure = null;
    public function getStateStructure()
    {
        if (is_null($this->_stateStructure)) {
            $this->_stateStructure = StateStructure::findOne($this->stateStructureId);
        }
        return $this->_stateStructure;
    }
    
    public function getCity()
    {
        return $this->hasOne(City::classname(), ['id' => 'cityId']);
    }
        
    public function getRegions()
    {
        return $this->hasMany(Region::classname(), ['stateId' => 'id'])->where(['dateDeleted' => null]);
    }
        
    public function getDistricts()
    {
        return $this->hasMany(ElectoralDistrict::classname(), ['stateId' => 'id']);
    }
    
    public function getCities()
    {
        return $this->hasMany(City::className(), ['regionId' => 'id'])
                ->via('regions');
    }
    
    public function getAgencies()
    {
        return $this->hasMany(Agency::className(), ['stateId' => 'id'])->where(['dateDeleted' => null]);
    }
    
    public function getPosts()
    {
        return $this->hasMany(AgencyPost::className(), ['stateId' => 'id']);
    }
    
    public function getParties()
    {
        return $this->hasMany(Party::className(), ['stateId' => 'id'])
                ->where(['dateDeleted' => null])
                ->andWhere(['is not', 'dateConfirmed', null]);
    }
    
    public function getPartiesUnconfirmed()
    {
        return $this->hasMany(Party::className(), ['stateId' => 'id'])
                ->where(['dateDeleted' => null, 'dateConfirmed' => null]);
    }
        
    public function getTiles()
    {
        return $this->hasMany(Tile::className(), ['regionId' => 'id'])
                ->via('regions');
    }
    
    public function getPops()
    {
        return $this->hasMany(Pop::className(), ['tileId' => 'id'])
                ->via('tiles');
    }
    
    public function getBills()
    {
        return $this->hasMany(Bill::className(), ['stateId' => 'id']);
    }
    
    public function getBillsActive()
    {
        return $this->hasMany(Bill::className(), ['stateId' => 'id'])->where(['dateFinished' => null]);
    }
    
    
    public function getBillsFinished()
    {
        return $this->hasMany(Bill::className(), ['stateId' => 'id'])->where(['is not', 'dateFinished', null]);
    }
    
    public function getLicenses()
    {
        return $this->hasMany(License::className(), ['stateId' => 'id'])
                ->where(['is not', 'dateGranted', null])
                ->andWhere('dateGranted < dateExpired');
    }
    
    public function getLicensesUnconfirmed()
    {
        return $this->hasMany(License::className(), ['stateId' => 'id'])
                ->where(['dateGranted' => null]);
    }
    
    public function getLicenseRules()
    {
        return $this->hasMany(LicenseRule::className(), ['stateId' => 'id']);
    }
    
    /**
     * 
     * @param integer $id
     * @return LicenseRule
     */
    public function getLicenseRuleByProto(int $id)
    {
        return $this->hasOne(LicenseRule::className(), ['stateId' => 'id'])->where(['protoId' => $id])->one();
    }
    
    public function getCompanies()
    {
        return $this->hasMany(Company::className(), ['stateId' => 'id']);
    }
    
    public function getCompaniesGovermentAndHalfGoverment()
    {
        return $this->hasMany(Company::className(), ['stateId' => 'id'])
                ->where(['or', ['isGoverment' => true], ['isHalfGoverment' => true]]);
    }
    
    public function getTaxpayersGoverment()
    {
        return array_merge($this->posts, $this->agencies, $this->regions, $this->cities, [$this]);
    }
    
    public function getLeaderPost()
    {
        $article = $this->constitution->getArticleByType(ConstitutionArticleType::LEADER_POST);
        if (!is_null($article)) {
            return AgencyPost::findByPk($article->value);
        } else {
            return null;
        }
    }

    private function getPolygonFilePath()
    {
        return Yii::$app->basePath.'/data/polygons/states/'.$this->id.'.json';        
    }
    
    public function calcPolygon()
    {
        return RegionCombiner::combine($this->getRegions());
    }
                
    private $_polygon = null;
    public function getPolygon($update = false)
    {
        if (is_null($this->_polygon) || $update) {
            $filePath = $this->getPolygonFilePath();
            if (!$update && file_exists($filePath)) {
                $this->_polygon = file_get_contents($filePath);
            } else {
                $this->_polygon = json_encode($this->calcPolygon());
                file_put_contents($filePath, $this->_polygon);
            }
        }
        return $this->_polygon;
    }
    
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->dateCreated = time();
        }
        return parent::beforeSave($insert);
    }
    
    public function updateParams($save = true, $polygon = true)
    {
                
        $this->govermentFormId = GovermentForm::calcForState($this);
        $this->stateStructureId = StateStructure::calcForState($this);
        
        $this->population = 0;
        foreach ($this->regions as $region) {
            $this->population += $region->population;
        }
        
        $this->nations = MyMathHelper::sumPercents($this->regions, 'nations', 'population', $this->population);
        $this->religions = MyMathHelper::sumPercents($this->regions, 'religions', 'population', $this->population);
        $this->classes = MyMathHelper::sumPercents($this->regions, 'classes', 'population', $this->population);
        $this->ideologies = MyMathHelper::sumPercents($this->regions, 'ideologies', 'population', $this->population);
        $this->genders = MyMathHelper::sumPercents($this->regions, 'genders', 'population', $this->population);
        $this->ages = MyMathHelper::sumPercents($this->regions, 'ages', 'population', $this->population);
        
        if ($polygon) {
            $this->getPolygon(true);
        }
        
        if ($save) {
            return $this->save();
        }
    }

    public static function getConstitutionOwnerType(): int
    {
        return ConstitutionOwnerType::STATE;
    }
    
    public function getIsPartiesCreatingAllowed()
    {
        /* @var $article Parties */
        $article = $this->constitution->getArticleByType(ConstitutionArticleType::PARTIES);
        return $article && ($article->value == Parties::NEED_CONFIRM || $article->value == Parties::ALLOWED);
    }
    
    /**
     * 
     * @param TaxPayer $taxPayer
     * @return boolean
     */
    public function isCompaniesCreatingAllowedFor($taxPayer)
    {
        /* @var $article Business */
        $article = $this->constitution->getArticleByType(ConstitutionArticleType::BUSINESS);
        if (is_null($article)) {
            return false;
        }
        if ($taxPayer->isTaxedInState($this->id)) {
            return !!$article->value;
        } else {
            return !!$article->value2;
        }
    }
    
    public function getRecommendedParliamentSize() : int
    {
        return $this->getDistricts()->count();
    }
    
    public function getLicenseGrantedTime(int $protoId) : int
    {
        return 365 * 24 * 60 * 60;
    }
    
    public function getNewLicense(int $companyId, int $protoId) : bool
    {
        $licenseRule = $this->getLicenseRuleByProto($protoId);
        $isNeedConfirmation = is_null($licenseRule) || $licenseRule->isNeedConfirmation;
        
        $license = new License([
            'protoId' => $protoId,
            'stateId' => $this->id,
            'companyId' => $companyId,
        ]);
        if (!$isNeedConfirmation) {
            $license->dateGranted = time();
            $license->dateExpired = time() + $this->getLicenseGrantedTime($protoId);
        }
        return $license->save();
    }

}
