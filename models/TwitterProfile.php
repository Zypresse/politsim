<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "twitterProfiles".
 *
 * @property integer $userId
 * @property string $nickname
 * @property integer $followersCount
 * @property integer $dateLastTweet
 * 
 * @property User $user
 * 
 */
class TwitterProfile extends \app\models\base\MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'twitterProfiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'nickname'], 'required'],
            [['userId', 'followersCount', 'dateLastTweet'], 'integer', 'min' => 0],
            [['nickname'], 'string', 'max' => 255],
            [['nickname'], 'unique'],
            [['userId'], 'unique'],
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
            'nickname' => Yii::t('app', 'Nickname'),
            'followersCount' => Yii::t('app', 'Followers Count'),
            'dateLastTweet' => Yii::t('app', 'Date Last Tweet'),
        ];
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
    
}
