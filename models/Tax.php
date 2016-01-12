<?php

namespace app\models;

use app\components\MyModel,
    app\models\State,
    app\models\TaxProto;

/**
 * Величина налога в стране. Таблица "taxes".
 *
 * @property integer $id
 * @property integer $proto_id
 * @property integer $state_id
 * @property double $value
 * 
 * @property TaxProto $proto
 * @property State $state
 */
class Tax extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'taxes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['proto_id', 'state_id', 'value'], 'required'],
            [['proto_id', 'state_id'], 'integer'],
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
            'proto_id' => 'Proto ID',
            'state_id' => 'State ID',
            'value' => 'Value',
        ];
    }
    
    public function getProto()
    {
        return $this->hasOne(TaxProto::className(), array('id' => 'proto_id'));
    }

    public function getState()
    {
        return $this->hasOne(State::className(), array('id' => 'state_id'));
    }
}
