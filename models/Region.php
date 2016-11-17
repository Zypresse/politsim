<?php

namespace app\models;

use Yii,
    app\components\TaxPayerModel,
    app\components\TileCombiner;

/**
 * Административный регион
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
 * @property integer $utr
 * 
 * @property string $polygon JSON
 *
 * @property State $state
 * @property City $city
 * @property City $biggestCity
 * @property City[] $cities
 * @property Tile[] $tiles
 * @property RegionConstitution $constitution
 * 
 */
class Region extends TaxPayerModel
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
            [['name', 'nameShort'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 6],
            [['flag', 'anthem'], 'string'],
            [['stateId', 'cityId', 'population', 'usersCount', 'usersFame', 'utr'], 'integer', 'min' => 0],
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
        return Utr::TYPE_REGION;
    }
        
    /**
     * Является ли плательщик правительством страны
     * @param integer $stateId
     * @return boolean
     */
    public function isGoverment($stateId)
    {
        return $this->stateId === $stateId;
    }
            
    /**
     * ID страны, в которой он платит налоги
     * @return integer
     */
    public function getTaxStateId()
    {
        return $this->stateId;
    }
    
    /**
     * Является ли налогоплательщиком государства
     * @param integer $stateId
     * @return boolean
     */
    public function isTaxedInState($stateId)
    {
        return $this->stateId === $stateId;
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
    
    public function getTiles()
    {
        return $this->hasMany(Tile::className(), ['regionId' => 'id']);
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'stateId']);
    }
    
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'cityId']);
    }
    
    public function getBiggestCity()
    {
        return $this->hasOne(City::className(), ['regionId' => 'id'])->orderBy(['population' => SORT_DESC]);
    }
    
    public function getCities()
    {
        return $this->hasMany(City::className(), ['regionId' => 'id']);
    }
    
    public function getConstitution()
    {
        return $this->hasOne(RegionConstitution::classname(), ['regionId' => 'id']);
    }
    
    private function getPolygonFilePath()
    {
        return Yii::$app->basePath.'/data/polygons/regions/'.$this->id.'.json';        
    }
    
    public function calcPolygon()
    {
        return TileCombiner::combine($this->getTiles());
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
    
    
    public function updateParams($save = true)
    {
        
        if (!$this->constitution) {
            $constitution = RegionConstitution::generate();
            $constitution->regionId = $this->id;
            $constitution->save();
            $this->refresh();
        }
                
        $this->population = 0;
        foreach ($this->tiles as $tile) {
            $this->population += $tile->population;
        }
        
        
        
        $this->getPolygon(true);
        
        if ($save) {
            return $this->save();
        }
    }
}
