<?php

namespace app\models\resurses;

use app\components\MyModel;

/**
 * This is the model class for table "resurses".
 *
 * @property integer $id
 * @property integer $factory_id
 * @property integer $proto_id
 * @property double $count
 *
 * @property proto\ResurseProto $proto
 * @property \app\models\factories\Factory $factory
 */
class Resurse extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resurses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['factory_id', 'proto_id', 'count'], 'required'],
            [['factory_id', 'proto_id'], 'integer'],
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
            'factory_id' => 'Factory ID',
            'proto_id' => 'Proto ID',
            'count' => 'Count',
        ];
    }
    
    public function getProto()
    {
        return $this->hasOne('app\models\resurses\proto\ResurseProto', array('id' => 'proto_id'));
    }

    public function getFactory()
    {
        return $this->hasOne('app\models\factories\Factory', array('id' => 'factory_id'));
    }
}