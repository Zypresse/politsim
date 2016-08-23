<?php

namespace app\models\resources;

use app\models\objects\MovableObject,
    app\models\resources\ResourceCost,
    app\models\resources\proto\ResourceProto,
    app\models\Place;

/**
 * This is the model class for table "resources".
 *
 * @property integer $id
 * @property integer $proto_id
 * @property integer $place_id
 * @property double $count
 * @property integer $quality from 1 to 10
 *
 * @property ResourceProto $proto
 * @property ResourceCost[] $costs
 * @property Place $place
 */
class Resource extends MovableObject
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resources';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['place_id', 'proto_id'], 'required'],
            [['place_id', 'proto_id', 'quality'], 'integer'],
            [['count'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'place_id' => 'Place ID',
            'proto_id' => 'Proto ID',
            'count' => 'Count',
            'quality' => 'Quality'
        ];
    }
    
    public function getProto()
    {
        return $this->hasOne(ResourceProto::className(), array('id' => 'proto_id'));
    }
    
    public function getCosts()
    {
        return $this->hasMany(ResourceCost::className(), array('resource_id' => 'id'));
    }
    
    public function getLocatedStateId() {
        return $this->place->object->getLocatedStateId();
    }

}