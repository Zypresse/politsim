<?php

namespace app\models\objects;

/**
 * 
 *
 * @property integer $place_id
 * 
 * @property Object $place
 * 
 * @author ilya
 */

class MovableObject extends Object {
    
    public function getPlace()
    {
        return $this->hasOne(Object::className(), array('id' => 'place_id'));
    }
    
    public function isStorable()
    {
        return false;
    }
    
}
