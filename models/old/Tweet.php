<?php

namespace app\models;

use Yii,
    yii\behaviors\TimestampBehavior,
    app\models\base\MyActiveRecord;

/**
 * This is the model class for table "tweets".
 *
 * @property integer $id
 * @property integer $userId
 * @property string $text
 * @property integer $audienceCoverage
 * @property integer $retweetsCount
 * @property integer $originalId
 * @property integer $dateCreated
 * @property integer $dateDeleted
 * 
 * @property User $user
 * @property Tweer $original
 * 
 */
class Tweet extends MyActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'dateCreated',
                'updatedAtAttribute' => false,
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tweets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId'], 'required'],
            [['userId', 'audienceCoverage', 'retweetsCount', 'originalId', 'dateCreated', 'dateDeleted'], 'string'],
            [['text'], 'string', 'max' => 255],
            [['originalId'], 'exist', 'skipOnError' => true, 'targetClass' => Tweet::className(), 'targetAttribute' => ['originalId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userId' => Yii::t('app', 'User ID'),
            'text' => Yii::t('app', 'Text'),
            'audienceCoverage' => Yii::t('app', 'Audience Coverage'),
            'retweetsCount' => Yii::t('app', 'Retweets Count'),
            'originalId' => Yii::t('app', 'Original ID'),
            'dateCreated' => Yii::t('app', 'Date Created'),
            'dateDeleted' => Yii::t('app', 'Date Deleted'),
        ];
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
    
    public function getOriginal()
    {
        return $this->hasOne(Tweer::className(), ['id' => 'originalId']);
    }
    
    public function getIsDeleted()
    {
        return !!$this->dateDeleted;
    }
    
}
