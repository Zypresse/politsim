<?php

namespace app\models;

use Yii,
    app\models\base\MyActiveRecord,
    yii\behaviors\TimestampBehavior;

/**
 * текстовые сообщения игроков
 *
 * @property integer $id
 * @property string $typeId  1 - лс, 2 - обсуждение законопроекта, етч.
 * @property string $senderId
 * @property string $recipientId
 * @property string $text
 * @property string $textHtml
 * @property string $dateCreated
 * @property string $dateUpdated
 * @property string $dateDeleted
 * 
 * @property User $sender
 */
class Message extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'dateCreated',
                'updatedAtAttribute' => 'dateUpdated',
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['typeId', 'senderId', 'recipientId', 'text', 'textHtml'], 'required'],
            [['typeId', 'senderId', 'recipientId', 'dateCreated', 'dateUpdated', 'dateDeleted'], 'integer', 'min' => 0],
            [['text', 'textHtml'], 'string'],
            [['senderId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['senderId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'typeId' => Yii::t('app', 'Type ID'),
            'senderId' => Yii::t('app', 'Sender ID'),
            'recipientId' => Yii::t('app', 'Recipient ID'),
            'text' => Yii::t('app', 'Text'),
            'textHtml' => Yii::t('app', 'Text Html'),
            'dateCreated' => Yii::t('app', 'Date Created'),
            'dateUpdated' => Yii::t('app', 'Date Updated'),
            'dateDeleted' => Yii::t('app', 'Date Deleted'),
        ];
    }
    
    public function actionSender()
    {
        return $this->hasOne(User::className(), ['id' => 'senderId']);
    }
}
