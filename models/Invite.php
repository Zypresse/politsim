<?php

namespace app\models;

use app\components\MyModel;

/**
 * This is the model class for table "invites".
 *
 * @property integer $id
 * @property string $hash
 * @property integer $uid
 * @property integer $time
 */
class Invite extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hash'], 'required'],
            [['hash'], 'string'],
            [['uid', 'time'], 'integer'],
            [['hash'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hash' => 'Hash',
            'uid' => 'Uid',
            'time' => 'Time',
        ];
    }
    
    /**
     * Использован ли инвайт
     * @return boolean
     */
    public function isUsed()
    {
        return !!$this->uid;
    }

    /**
     * 
     * @param string $hash
     * @return Invite
     */
    public static function findByHash($hash)
    {
        return static::find()->where(['hash' => $hash])->one();
    }
}
