<?php

namespace app\models\factories;

use app\components\MyModel,
    app\models\factories\Factory,
    app\models\resources\proto\ResourceProto,
    app\models\Holding,
    app\models\State;

/**
 * This is the model class for table "factories_autobuy_settings".
 *
 * @property integer $id
 * @property integer $factory_id
 * @property integer $resource_proto_id
 * @property double $max_cost
 * @property integer $min_quality
 * @property double $count
 * @property integer $holding_id
 * @property integer $state_id
 * 
 * @property Factory $factory
 * @property ResourceProto $resourceProto
 * @property Holding $holding
 * @property State $state
 */
class FactoryAutobuySettings extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factories_autobuy_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['factory_id', 'resource_proto_id', 'count'], 'required'],
            [['factory_id', 'resource_proto_id', 'holding_id', 'state_id', 'min_quality'], 'integer'],
            [['max_cost', 'count'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'factory_id' => 'Factory ID',
            'resource_proto_id' => 'Resource Proto ID',
            'max_cost' => 'Max Cost',
            'min_quality' => 'Min Quality',
            'count' => 'Count',
            'holding_id' => 'Holding ID',
            'state_id' => 'State ID',
        ];
    }
    
    public function getResourceProto()
    {
        return $this->hasOne(ResourceProto::className(), array('id' => 'resource_proto_id'));
    }
    
    public function getFactory()
    {
        return $this->hasOne(Factory::className(), array('id' => 'factory_id'));
    }
    
    public function getHolding()
    {
        return $this->hasOne(Holding::className(), array('id' => 'holding_id'));
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), array('id' => 'state_id'));
    }
}
