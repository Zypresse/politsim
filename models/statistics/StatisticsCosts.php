<?php

namespace app\models\statistics;

use app\models\resources\proto\ResourceProto,
    app\models\resources\ResourceCost,
    app\models\resources\Resource,
    app\models\State;

/**
 * This is the model class for table "statistics_costs".
 *
 * @property integer $id
 * @property integer $resource_proto_id
 * @property integer $timestamp
 * @property double $value
 * @property integer $state_id
 * 
 * @property ResourceProto $resourceProto
 * @property State $state
 */
class StatisticsCosts extends Statistics
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statistics_costs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resource_proto_id', 'value'], 'required'],
            [['resource_proto_id', 'timestamp', 'state_id'], 'integer'],
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
            'resource_proto_id' => 'Resource Proto ID',
            'timestamp' => 'Timestamp',
            'value' => 'Value',
            'state_id' => 'State ID',
        ];
    }
    
    public function getResourceProto()
    {
        return $this->hasOne(ResourceProto::className(), array('id' => 'resource_proto_id'));
    }
        
    public function getState()
    {
        return $this->hasOne(State::className(), array('id' => 'state_id'));
    }
    
    public function updateValue()
    {
        $cost = ResourceCost::find()
            ->join('LEFT JOIN', Resource::tableName(), Resource::tableName().'.id = '.ResourceCost::tableName().'.resource_id')
            ->where([Resource::tableName().'.proto_id'=>$this->resource_proto_id])
            ->andWhere(['>',Resource::tableName().'.count',0])
            ->andWhere(['holding_id'=>null])
            ->andWhere(['state_id'=>null])
            ->orderBy(ResourceCost::tableName().'.cost ASC, '.Resource::tableName().'.quality DESC')
            ->groupBy(Resource::tableName().'.place_id')
            ->one();
        if (is_null($cost)) {
            $this->value = 0;
        } else {
            $this->value = $cost->cost;
        }
    }
    
}
