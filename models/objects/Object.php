<?php

namespace app\models\objects;

use app\components\MyModel,
    app\models\objects\proto\ObjectProto;

/**
 * 
 *
 * @property MovableObject[] $content
 * @property proto\ObjectProto $proto
 * 
 * @author ilya
 */
class Object extends MyModel {
        
    public function getProto()
    {
        return $this->hasOne(ObjectProto::className(), array('id' => 'proto_id'));
    }
        
    public function getLocatedStateId()
    {
        throw new \yii\base\Exception("not redefined");
    }
}
