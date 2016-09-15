<?php

namespace app\models;

use Yii,
    app\components\MyModel,
    app\components\TaxPayer,
    app\components\RegionCombiner;

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
 * @property Constitution $constitution
 * @property GovermentForm $govermentForm
 * @property StateStructure $stateStructure
 * @property Region[] $regions
 *
 * @author ilya
 */
class State extends MyModel implements TaxPayer
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
     * Возвращает ИНН
     * @return int
     */
    public function getUtr()
    {
        if (is_null($this->utr)) {
            $u = Utr::findOneOrCreate(['objectId' => $this->id, 'objectType' => $this->getUtrType()]);
            if ($u) {
                $this->utr = $u->id;
                $this->save();
            }
        } 
        return $this->utr;
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
     * Возвращает баланс плательщика в у.е.
     * @param integer $currencyId
     * @return double
     */
    public function getBalance($currencyId)
    {
        $money = resources\Resource::findOrCreate([
            'protoId' => 1, // деньги
            'subProtoId' => $currencyId,
            'masterId' => $this->getUtr()
        ], false, [
            'count' => 0
        ]);
        return $money->count;
    }
    
    /**
     * Меняет баланс плательщика
     * @param integer $currencyId
     * @param double $delta
     */
    public function changeBalance($currencyId, $delta)
    {
        $money = resources\Resource::findOrCreate([
            'protoId' => 1, // деньги
            'subProtoId' => $currencyId,
            'masterId' => $this->getUtr()
        ], false, [
            'count' => 0
        ]);
        $money->count += $delta;
        return $money->save();
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
        return $this->hasOne(Constitution::classname(), ['stateId' => 'id']);
    }
    
    public function getRegions()
    {
        return $this->hasMany(Region::classname(), ['stateId' => 'id']);
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
    public function getPolygon()
    {
        if (is_null($this->_polygon)) {
            $filePath = $this->getPolygonFilePath();
            if (file_exists($filePath)) {
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
    
    public function updateParams($save = true)
    {
        
        if (!$this->constitution) {
            $constitution = Constitution::generate();
            $constitution->stateId = $this->id;
            $constitution->save();
            $this->refresh();
        }
        
        if ($this->constitution->partyPolicy == Constitution::PARTY_POLICY_FREE) {
            $this->govermentFormId = GovermentForm::REPUBLIC;
        } else {
            $this->govermentFormId = GovermentForm::DICTATURE;
        }
        
        $this->population = 0;
        foreach ($this->regions as $region) {
            $this->population += $region->population;
        }
        
        $this->_polygon = json_encode($this->calcPolygon());
        file_put_contents($this->getPolygonFilePath(), $this->_polygon);
        
        if ($save) {
            return $this->save();
        }
    }
        
}
