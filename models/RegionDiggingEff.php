<?php

namespace app\models;

use app\components\MyModel,
    app\models\Region,
    app\models\resources\proto\ResourceProto;

/**
 * This is the model class for table "regions_digging_effectiveness".
 *
 * @property integer $id
 * @property integer $region_id
 * @property integer $resource_proto_id
 * @property integer $group_id
 * @property double $k
 * 
 * @property Region $region
 * @property resources\proto\ResourceProto $resourceProto
 */
class RegionDiggingEff extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'regions_digging_effectiveness';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['region_id', 'resource_proto_id', 'group_id', 'k'], 'required'],
            [['region_id', 'resource_proto_id', 'group_id'], 'integer'],
            [['k'], 'number'], 
            [['region_id', 'resource_proto_id'], 'unique', 'targetAttribute' => ['region_id', 'resource_proto_id'], 'message' => 'The combination of Region ID and Resource Proto ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'region_id' => 'Region ID',
            'resource_proto_id' => 'Resource Proto ID',
            'group_id' => 'Group ID',
            'k' => 'K',
        ];
    }
    
    public function getRegion()
    {
        return $this->hasOne(Region::className(), array('id' => 'region_id'));
    }
    
    public function getResourceProto()
    {
        return $this->hasOne(ResourceProto::className(), array('id' => 'resource_proto_id'));
    }
    
}