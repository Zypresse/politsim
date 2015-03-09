<?php

namespace app\models;

use Yii;
use app\components\MyModel;
use app\components\vkapi\VkNotification;

/**
 * This is the model class for table "notifications".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $text
 */
class Notification extends MyModel
{
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
        return $this->hasOne('app\models\User', array('id' => 'uid'));
    }
    
    public function afterSave($insert,$changedAttributes)
    {
        if ($insert && $this->user->uid_vk) {
            VkNotification::send($this->user->uid_vk, $this->text);
        }
        
        return parent::afterSave($insert,$changedAttributes);
    }
    
    public static function send($uid,$text)
    {
        $notification = new Notification();
        $notification->text = $text;
        $notification->uid = $uid;
        return $notification->save();
    }
}
