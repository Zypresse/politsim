<?php

namespace app\models\factories\proto;

use app\components\MyModel;

/**
 * This is the model class for table "regions_digging_effectiveness".
 *
 * @property integer $id
 * @property integer $region_id
 * @property integer $resurse_proto_id
 */
class RegionDiggingEffectiveness extends MyModel
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
            [['region_id', 'resurse_proto_id'], 'required'],
            [['region_id', 'resurse_proto_id'], 'integer'],
            [['region_id', 'resurse_proto_id'], 'unique', 'targetAttribute' => ['region_id', 'resurse_proto_id'], 'message' => 'The combination of Region ID and Resurse Proto ID has already been taken.']
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
            'resurse_proto_id' => 'Resurse Proto ID',
        ];
    }
}