<?php

namespace app\models;

use app\components\MyModel,
    app\components\vkapi\VkNotification,
    app\models\User;

/**
 * Уведомления. В будущем будет типа системы важных сообщений. Таблица "notifications".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $text
 * 
 * @property User $user Пользователь
 */
class Notification extends MyModel {

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'text'], 'required'],
            [['uid'], 'integer'],
            [['text'], 'string', 'max' => 1023]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'text' => 'Text',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), array('id' => 'uid'));
    }

    /**
     * Отправляем уведомление в вк, если возможно
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            foreach ($this->user->accounts as $acc) {
                if ($acc->source === "vkontakte") {
                    VkNotification::send($acc->source_id, $this->text);
                }
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Отправка уведомления
     * @param intval $uid
     * @param string $text
     * @return boolean
     */
    public static function send($uid, $text)
    {
        $notification = new Notification();
        $notification->text = $text;
        $notification->uid = $uid;
        return $notification->save();
    }

}
