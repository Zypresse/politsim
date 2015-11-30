<?php

namespace app\models\objects;

use app\models\Place;

/**
 * 
 *
 * @property integer $place_id
 * 
 * @property Place $place
 * 
 * @author ilya
 */

class MovableObject extends Object {
    
    public function getPlace()
    {
        return $this->hasOne(Place::className(), array('id' => 'place_id'))->one()->getObject();
    }
    
    public function isStorable()
    {
        return false;
    }
    
}
