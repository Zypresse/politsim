<?php

namespace app\models;

use app\models\base\MyActiveRecord;

/**
 * Уведомление, направленное юзеру
 *
 * @property integer $id 
 * @property integer $protoId
 * @property integer $userId
 * @property string $text
 * @property string $textShort
 * @property integer $dateCreated
 * @property integer $dateReaded
 * 
 * @property NotificationProto $proto
 * 
 */
class Notification extends MyActiveRecord
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
            [['protoId', 'userId'], 'required'],
            [['protoId'], 'integer', 'min' => 0, 'max' => 99999],
            [['userId', 'dateCreated', 'dateReaded'], 'integer', 'min' => 0],
            [['text'], 'string'],
            [['textShort'], 'string', 'max' => 255],
        ];
    }
    
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->dateCreated = time();
        }
        return parent::beforeSave($insert);
    }
    
    public function getProto()
    {
        return NotificationProto::findOne($this->protoId);
    }
    
    public function getText()
    {
        return $this->text ? $this->text : $this->proto->text;
    }
    
    public function getTextShort()
    {
        return $this->textShort ? $this->textShort : $this->proto->textShort;
    }
    
    public function getIcon()
    {
        return $this->proto->icon;
    }
    
    public function getIconBg()
    {
        return $this->proto->iconBg;
    }
    
    /**
     * 
     * @param integer $userId
     * @return \yii\db\ActiveQuery
     */
    public static function findByUser($userId)
    {
        return static::find()->where([
            'userId' => $userId
        ]);
    }
    
    public static function markAllAsRead($userId)
    {
        return static::updateAll([
            'dateReaded' => time()
        ], [
            'userId' => $userId,
            'dateReaded' => null
        ]);
    }
    
}
