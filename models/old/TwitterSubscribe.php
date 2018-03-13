<?php

namespace app\models;

use Yii,
    app\models\base\MyActiveRecord;

/**
 * This is the model class for table "twitterSubscribes".
 *
 * @property integer $userId
 * @property integer $followerId
 * @property integer $dateSubcribed
 * 
 * @property User $user
 * @property User $follower
 */
class TwitterSubscribe extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'twitterSubscribes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'followerId', 'dateSubcribed'], 'required'],
            [['userId', 'followerId', 'dateSubcribed'], 'integer', 'min' => 0],
            [['userId', 'followerId'], 'unique', 'targetAttribute' => ['userId', 'followerId'], 'message' => 'The combination of User ID and Follower ID has already been taken.'],
            [['followerId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['followerId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => Yii::t('app', 'User ID'),
            'followerId' => Yii::t('app', 'Follower ID'),
            'dateSubcribed' => Yii::t('app', 'Date Subcribed'),
        ];
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
    
    public function getFollower()
    {
        return $this->hasOne(User::className(), ['id' => 'followerId']);
    }
    
}
