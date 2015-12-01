<?php

namespace app\models\factories;

use app\components\MyModel,
    app\models\factories\Factory,
    app\models\resurses\proto\ResurseProto;

/**
 * This is the model class for table "factory_autobuy_settings".
 *
 * @property integer $id
 * @property integer $factory_id
 * @property integer $resurse_proto_id
 * @property integer $type
 * @property double $max_cost
 * @property double $count
 * 
 * @property Factory $factory
 * @property ResurseProto $resurseProto
 */
class FactoryAutobuySettings extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factory_autobuy_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['factory_id', 'resurse_proto_id', 'count'], 'required'],
            [['factory_id', 'resurse_proto_id', 'type'], 'integer'],
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
            'resurse_proto_id' => 'Resurse Proto ID',
            'type' => 'Type',
            'max_cost' => 'Max Cost',
            'count' => 'Count',
        ];
    }
    
    public function getResurseProto()
    {
        return $this->hasOne(ResurseProto::className(), array('id' => 'resurse_proto_id'));
    }
    
    public function getFactory()
    {
        return $this->hasOne(Factory::className(), array('id' => 'factory_id'));
    }
}
