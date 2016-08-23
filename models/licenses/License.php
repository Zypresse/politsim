<?php

namespace app\models\licenses;

use app\components\MyModel,
    app\models\Holding,
    app\models\State,
    app\models\licenses\proto\LicenseProto as Proto;

/**
 * Привязка холдингов к лицензиям. Таблица "licenses".
 *
 * @property integer $id
 * @property integer $holding_id
 * @property integer $proto_id
 * @property integer $state_id
 * 
 * @property Holding $holding Акционерное общество
 * @property Proto $proto Тип лицензии
 * @property State $state Государство, выдавшее лицензию
 */
class License extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'licenses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['holding_id', 'proto_id', 'state_id'], 'required'],
            [['holding_id', 'proto_id', 'state_id'], 'integer'],
            [['holding_id', 'proto_id', 'state_id'], 'unique', 'targetAttribute' => ['holding_id', 'proto_id', 'state_id'], 'message' => 'The combination of Holding ID, Proto ID and State ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'holding_id' => 'Holding ID',
            'proto_id' => 'License ID',
            'state_id' => 'State ID',
        ];
    }

    public function getHolding()
    {
        return $this->hasOne(Holding::className(), array('id' => 'holding_id'));
    }

    public function getProto()
    {
        return $this->hasOne(Proto::className(), array('id' => 'proto_id'));
    }

    public function getState()
    {
        return $this->hasOne(State::className(), array('id' => 'state_id'));
    }

}
