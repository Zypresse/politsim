<?php

namespace app\models\politics;

use Yii,
    app\models\economics\UtrType,
    app\models\politics\constitution\Constitution,
    app\models\politics\constitution\ConstitutionOwner,
    app\models\politics\constitution\ConstitutionOwnerType,
    app\components\TileCombiner,
    app\components\MyMathHelper,
    app\models\Tile,
    app\models\population\Pop,
    app\models\population\PseudoPop;

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
 * @property Pop[] $pops
 * @property PseudoPop[] $pseudoPops
 * @property Constitution $constitution
 */
class City extends ConstitutionOwner
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
        return UtrType::CITY;
    }
    
    /**
     * Является ли плательщик правительством страны
     * @param integer $stateId
     * @return boolean
     */
    public function isGoverment(int $stateId)
    {
        return $this->region && (int)$this->region->stateId === $stateId;
    }
            
    /**
     * ID страны, в которой он платит налоги
     * @return integer
     */
    public function getTaxStateId()
    {
        return $this->region ? (int)$this->region->stateId : 0;
    }
    
    /**
     * Является ли налогоплательщиком государства
     * @param integer $stateId
     * @return boolean
     */
    public function isTaxedInState(int $stateId)
    {
        return $this->region && (int)$this->region->stateId === $stateId;
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
    
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'regionId']);
    }

    public function getTiles()
    {
        return $this->hasMany(Tile::className(), ['cityId' => 'id']);
    }
    
    public function getPops()
    {
        return $this->hasMany(Pop::className(), ['tileId' => 'id'])->via('tiles');
    }
    
    public function getPseudoPops()
    {
        $pops = [];
        foreach ($this->pops as $pop) {
            $pops = array_merge($pops, $pop->getPseudoGroups());
        }
        return PseudoPop::unityIgnoreTile($pops);
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
    
    public function updateParams($save = true, $polygon = true)
    {
                        
        $this->population = 0;
        foreach ($this->tiles as $tile) {
            $tile->population = 0;
            foreach ($tile->pops as $pop) {
                $tile->population += $pop->count;
            }
//            $tile->save();
            $this->population += $tile->population;
        }
        
        $this->religions = MyMathHelper::sumPercents($this->pops, 'religions', 'count', $this->population);
        $this->ideologies = MyMathHelper::sumPercents($this->pops, 'ideologies', 'count', $this->population);
        $this->genders = MyMathHelper::sumPercents($this->pops, 'genders', 'count', $this->population);
        $this->ages = MyMathHelper::sumPercents($this->pops, 'ages', 'count', $this->population);
        
        $nations = [];
        $classes = [];
        
        $regions = [];
        foreach ($this->tiles as $tile) {
            
            if (isset($regions[$tile->regionId])) {
                $regions[$tile->regionId]++;
            } else {
                $regions[$tile->regionId] = 1;
            }
            
            foreach ($tile->pops as $pop) {
                if (isset($nations[$pop->nationId])) {
                    $nations[$pop->nationId] += $pop->count;
                } else {
                    $nations[$pop->nationId] = $pop->count;
                }
                if (isset($classes[$pop->classId])) {
                    $classes[$pop->classId] += $pop->count;
                } else {
                    $classes[$pop->classId] = $pop->count;
                }
            }
        }
        
        $maxId = $maxCount = 0;
        foreach ($regions as $id => $count) {
            if ($count > $maxCount) {
                $maxCount = $count;
                $maxId = $id;
            }
        }
        $this->regionId = $maxId;
        
        foreach ($nations as $nationId => $count) {
            $nations[$nationId] = round($count / $this->population * 100,2);
        }
        foreach ($classes as $classId => $count) {
            $classes[$classId] = round($count / $this->population * 100,2);
        }
        $this->nations = json_encode($nations);
        $this->classes = json_encode($classes);
        
        if ($polygon) {
            $this->getPolygon(true);
        }
        
        if ($save) {
            return $this->save();
        }
    }

    public static function getConstitutionOwnerType(): int
    {
        return ConstitutionOwnerType::CITY;
    }

}
