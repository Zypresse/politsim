<?php

namespace app\models\politics\elections;

use Yii,
    app\models\base\MyActiveRecord,
    app\models\politics\State,
    app\models\Tile,
    app\models\population\Pop,
    app\components\TileCombiner;

/**
 * Вещь, передаваемая в сделке
 *
 * @property integer $id
 * @property integer $stateId
 * @property string $name
 * @property string $nameShort
 * 
 * @property State $state
 * @property Tile[] $tiles
 * @property Pop[] $pops
 * 
 */
class ElectoralDistrict extends MyActiveRecord
{
        
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'electoralDistricts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stateId', 'name', 'nameShort'], 'required'],
            [['stateId'], 'integer', 'min' => 0],
            [['name'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 6],
            [['stateId'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['stateId' => 'id']],
        ];
    }
        
    public function getState()
    {
        return $this->hasOne(State::classname(), ['id' => 'stateId']);
    }
    
    public function getTiles()
    {
        return $this->hasMany(Tile::className(), ['electoralDistrictId' => 'id']);
    }
    
    public function getPops()
    {
        return $this->hasMany(Pop::className(), ['tileId' => 'id'])
                ->via('tiles');
    }
    
    private function getPolygonFilePath()
    {
        return Yii::$app->basePath.'/data/polygons/electoral-districts/'.$this->id.'.json';        
    }
    
    public function calcPolygon()
    {
        return TileCombiner::combine($this->getTiles());
    }
    
    private $_polygon = null;
    public function getPolygon($update = false)
    {
        if (is_null($this->_polygon)) {
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
        
        $this->refresh();
                
        if ($polygon) {
            $this->getPolygon(true);
        }
        
        if ($save) {
            return $this->save();
        }
        
    }
}