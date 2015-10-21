<?php

namespace app\models\objects;

use app\components\MyModel,
    app\models\objects\proto\ObjectProto,
    app\models\resurses\Resurse;

/**
 * 
 *
 * @property MovableObject[] $content
 * @property proto\ObjectProto $proto
 * 
 * @author ilya
 */
class Object extends MyModel {
    
    public function getContent()
    {
        return $this->hasMany(Resurse::className(),['place_id' => 'id']);
    }
    
    public function getProto()
    {
        return $this->hasOne(ObjectProto::className(), array('id' => 'proto_id'));
    }
    
}
