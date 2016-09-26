<?php

namespace app\models;

use Yii,
    app\components\TaxPayerModel;

/**
 * Город
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
 */
class City extends TaxPayerModel
{
    
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
            [['name', 'nameShort'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 6],
            [['flag', 'anthem'], 'string'],
            [['regionId', 'population', 'usersCount', 'usersFame', 'utr'], 'integer', 'min' => 0],
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
        return Utr::TYPE_CITY;
    }
    
    /**
     * Является ли плательщик правительством страны
     * @param integer $stateId
     * @return boolean
     */
    public function isGoverment($stateId)
    {
        return $this->region && $this->region->stateId === $stateId;
    }
            
    /**
     * ID страны, в которой он платит налоги
     * @return integer
     */
    public function getTaxStateId()
    {
        return $this->region ? $this->region->stateId : 0;
    }
    
    /**
     * Является ли налогоплательщиком государства
     * @param integer $stateId
     * @return boolean
     */
    public function isTaxedInState($stateId)
    {
        return $this->region && $this->region->stateId === $stateId;
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
    
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'regionId']);
    }

    public function getTiles()
    {
        return $this->hasMany(Tile::className(), ['regionId' => 'id']);
    }
        
    private function getPolygonFilePath()
    {
        return Yii::$app->basePath.'/data/polygons/cities/'.$this->id.'.json';        
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
