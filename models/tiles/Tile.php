<?php

namespace app\models\tiles;

use app\components\MyModel,
    app\models\Region,
    app\models\tiles\TileFactory;

/**
 * Тайл карты. Шестиугольник шириной 0.1 градуса. Таблица "tiles".
 *
 * @property integer $id
 * @property integer $x ВЕРТИКАЛЬНАЯ координата с нулём на экваторе
 * @property integer $y ГОРИЗОНТАЛЬНАЯ координата с нулём у Гринвича
 * @property double $w_lat широта западного угла
 * @property double $w_lng долгота западного угла
 * @property double $nw_lat широта северо-западного угла
 * @property double $nw_lng долгота северо-западного угла
 * @property double $ne_lat широта северо-восточного угла
 * @property double $ne_lng долгота северо-восточного угла
 * @property double $e_lat широта восточного угла
 * @property double $e_lng долгота восточного угла
 * @property double $se_lat широта юго-восточного угла
 * @property double $se_lng долгота юго-восточного угла
 * @property double $sw_lat широта юго-западного угла
 * @property double $sw_lng долгота юго-западного угла
 * @property double $center_lat широта центра
 * @property double $center_lng долгота центра
 * @property integer $is_water флаг возможности перемещения кораблей
 * @property integer $is_land флаг возможности перемещения СВ
 * @property integer $is_mountains флаг осложнённого или невозможного перемещения СВ
 * @property integer $population
 * @property integer $region_id
 * 
 * @property Region $region
 */
class Tile extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['x', 'y', 'w_lat', 'w_lng', 'nw_lat', 'nw_lng', 'ne_lat', 'ne_lng', 'e_lat', 'e_lng', 'se_lat', 'se_lng', 'sw_lat', 'sw_lng', 'center_lat', 'center_lng'], 'required'],
            [['x', 'y', 'is_water', 'is_land', 'is_mountains', 'population', 'region_id'], 'integer'],
            [['w_lat', 'w_lng', 'nw_lat', 'nw_lng', 'ne_lat', 'ne_lng', 'e_lat', 'e_lng', 'se_lat', 'se_lng', 'sw_lat', 'sw_lng', 'center_lat', 'center_lng'], 'number'],
            [['x', 'y'], 'unique', 'targetAttribute' => ['x', 'y'], 'message' => 'The combination of X and Y has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'x' => 'X',
            'y' => 'Y',
            'w_lat' => 'W Lat',
            'w_lng' => 'W Lng',
            'nw_lat' => 'Nw Lat',
            'nw_lng' => 'Nw Lng',
            'ne_lat' => 'Ne Lat',
            'ne_lng' => 'Ne Lng',
            'e_lat' => 'E Lat',
            'e_lng' => 'E Lng',
            'se_lat' => 'Se Lat',
            'se_lng' => 'Se Lng',
            'sw_lat' => 'Sw Lat',
            'sw_lng' => 'Sw Lng',
            'center_lat' => 'Center Lat',
            'center_lng' => 'Center Lng',
            'is_water' => 'Is Water',
            'is_land' => 'Is Land',
            'is_mountains' => 'Is Mountains',
            'population' => 'Population', 
            'region_id' => 'Region ID', 
        ];
    }

    /**
     * @inheritdoc
     * @return TileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TileQuery(get_called_class());
    }
    
    public function getRegion()
    {
        return $this->hasOne(Region::className(), array('id' => 'region_id'));
    }
    
    public function getW_lat()
    {
        return $this->center_lat;
    }
    
    public function getW_lng()
    {
        return $this->center_lng + TileFactory::OUTER_RADIUS * -1;
    }
    
    public function getE_lat()
    {
        return $this->center_lat;
    }
    
    public function getE_lng()
    {
        return $this->center_lng + TileFactory::OUTER_RADIUS;
    }
    
    public function getSe_lat()
    {
        return $this->center_lat + TileFactory::OUTER_RADIUS * -0.866 * TileFactory::correctLat($this->center_lat);
    }
    
    public function getSe_lng()
    {
        return $this->center_lng + TileFactory::OUTER_RADIUS * 0.5;
    }
    
    public function getSw_lat()
    {
        return $this->center_lat + TileFactory::OUTER_RADIUS * -0.866 * TileFactory::correctLat($this->center_lat);
    }
    
    public function getSw_lng()
    {
        return $this->center_lng + TileFactory::OUTER_RADIUS * -0.5;
    }
    
    public function getNw_lat()
    {
        return $this->center_lat + TileFactory::OUTER_RADIUS * 0.866 * TileFactory::correctLat($this->center_lat);
    }
    
    public function getNw_lng()
    {
        return $this->center_lng + TileFactory::OUTER_RADIUS * -0.5;
    }
    
    public function getNe_lat()
    {
        return $this->center_lat + TileFactory::OUTER_RADIUS * 0.866 * TileFactory::correctLat($this->center_lat);
    }
    
    public function getNe_lng()
    {
        return $this->center_lng + TileFactory::OUTER_RADIUS * 0.5;
    }
    
}