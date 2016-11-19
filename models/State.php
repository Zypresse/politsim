<?php

namespace app\models;

use Yii,
    app\components\TaxPayerModel,
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
 * 
 * @property City $city
 * @property StateConstitution $constitution
 * @property GovermentForm $govermentForm
 * @property StateStructure $stateStructure
 * @property Region[] $regions
 * @property Agency[] $agencies
 * @property Party[] $parties
 *
 * @author ilya
 */
class State extends TaxPayerModel
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
        return Utr::TYPE_STATE;
    }
        
    /**
     * Является ли плательщик правительством страны
     * @param integer $stateId
     * @return boolean
     */
    public function isGoverment($stateId)
    {
        return $this->id === $stateId;
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
    public function isTaxedInState($stateId)
    {
        return $this->id === $stateId;
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
    public function isUserController($userId)
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
    
    public function getConstitution()
    {
        return $this->hasOne(StateConstitution::classname(), ['stateId' => 'id']);
    }
    
    public function getRegions()
    {
        return $this->hasMany(Region::classname(), ['stateId' => 'id']);
    }
    
    public function getAgencies()
    {
        return $this->hasMany(Agency::className(), ['stateId' => 'id'])->where(['dateDeleted' => null]);
    }
    
    public function getParties()
    {
        return $this->hasMany(Party::className(), ['stateId' => 'id'])->where(['dateDeleted' => null]);
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
        
        if (!$this->constitution) {
            $constitution = StateConstitution::generate();
            $constitution->stateId = $this->id;
            $constitution->save();
            $this->refresh();
        }
        
        if ($this->constitution->partyPolicy == StateConstitution::PARTY_POLICY_FREE) {
            $this->govermentFormId = GovermentForm::REPUBLIC;
        } else {
            $this->govermentFormId = GovermentForm::DICTATURE;
        }
        
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
        
}
