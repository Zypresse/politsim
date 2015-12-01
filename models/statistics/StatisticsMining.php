<?php

namespace app\models\statistics;

use app\models\resurses\proto\ResurseProto,
    app\models\Holding,
    app\models\Region,
    app\models\State;

/**
 * This is the model class for table "statistics_mining".
 *
 * @property integer $id
 * @property integer $resurse_proto_id
 * @property integer $timestamp
 * @property double $value
 * @property integer $holding_id
 * @property integer $region_id
 * @property integer $state_id
 * 
 * @property ResurseProto $resurseProto
 * @property Holding $holding
 * @property Region $region
 * @property State $state
 */
class StatisticsMining extends Statistics
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statistics_mining';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resurse_proto_id', 'value'], 'required'],
            [['resurse_proto_id', 'timestamp', 'holding_id', 'region_id', 'state_id'], 'integer'],
            [['value'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resurse_proto_id' => 'Resurse Proto ID',
            'timestamp' => 'Timestamp',
            'value' => 'Value',
            'holding_id' => 'Holding ID',
            'region_id' => 'Region ID',
            'state_id' => 'State ID',
        ];
    }
    
    public function getResurseProto()
    {
        return $this->hasOne(ResurseProto::className(), array('id' => 'resurse_proto_id'));
    }
    
    public function getHolding()
    {
        return $this->hasOne(Holding::className(), array('id' => 'holding_id'));
    }
    
    public function getRegion()
    {
        return $this->hasOne(Region::className(), array('id' => 'region_id'));
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), array('id' => 'state_id'));
    }
    
}
