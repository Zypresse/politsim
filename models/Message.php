<?php

namespace app\models;

use Yii,
    app\models\base\MyActiveRecord,
    yii\behaviors\TimestampBehavior,
    bupy7\bbcode\BBCodeBehavior,
    yii\helpers\Html;

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
            [
                'class' => BBCodeBehavior::className(),
                'attribute' => 'text',
                'saveAttribute' => 'textHtml',
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['typeId', 'senderId', 'recipientId', 'text'], 'required'],
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
    
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'senderId']);
    }
    
    public function beforeSave($insert)
    {
        $this->text = Html::encode($this->text);
        return parent::beforeSave($insert);
    }
}
