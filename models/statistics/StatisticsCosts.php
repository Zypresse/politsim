<?php

namespace app\models\statistics;

use app\models\resurses\proto\ResurseProto,
    app\models\resurses\ResurseCost,
    app\models\State;

/**
 * This is the model class for table "statistics_costs".
 *
 * @property integer $id
 * @property integer $resurse_proto_id
 * @property integer $timestamp
 * @property double $value
 * @property integer $state_id
 * 
 * @property ResurseProto $resurseProto
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
            [['resurse_proto_id', 'value'], 'required'],
            [['resurse_proto_id', 'timestamp', 'state_id'], 'integer'],
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
            'state_id' => 'State ID',
        ];
    }
    
    public function getResurseProto()
    {
        return $this->hasOne(ResurseProto::className(), array('id' => 'resurse_proto_id'));
    }
        
    public function getState()
    {
        return $this->hasOne(State::className(), array('id' => 'state_id'));
    }
    
    public function updateValue()
    {
        $cost = ResurseCost::find()
            ->join('LEFT JOIN', 'resurses', 'resurses.id = resurse_costs.resurse_id')
            ->where(["resurses.proto_id"=>$this->resurse_proto_id])
            ->andWhere([">","resurses.count",0])
            ->andWhere(['holding_id'=>null])
            ->andWhere(['state_id'=>null])
            ->orderBy('cost ASC, resurses.quality DESC')
            ->groupBy('resurses.place_id')
            ->one();
        if (is_null($cost)) {
            $this->value = 0;
        } else {
            $this->value = $cost->cost;
        }
    }
    
}
