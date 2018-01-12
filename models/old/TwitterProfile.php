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
 * @property TwitterSubscribe[] $subscribitionsModels
 * @property TwitterSubscribe[] $subscribersModels
 * @property TwitterProfile[] $subscribitions
 * @property TwitterProfile[] $subscribers
 * @property Tweet[] $feed
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
            [['nickname'], 'string', 'max' => 255, 'min' => 3],
            [['nickname'], 'unique'],
            [['nickname'], 'validateNickname'],
            [['nickname'], 'filter', 'filter' => 'strtolower'],
            [['userId'], 'unique', 'message' => Yii::t('app', 'Nickname allready registered')],
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
    
    public function validateNickname($attribute, $params)
    {
        if (preg_match("/[^qwertyuiopadsfghjklzxcvbnm0123456789]/i", $this->$attribute)) {
            $this->addError($attribute, Yii::t('app', 'Allowed only latin letters and digits'));
        }
        
        if (preg_match("/[0123456789]/i", substr($this->$attribute, 0, 1))) {
            $this->addError($attribute, Yii::t('app', 'Nickname could be starts from letter'));
        }
        
        if (in_array($this->$attribute, ['admin', 'administrator', 'root', 'moder', 'moderator', 'game', 'politsim', 'system', 'auto'])) {
            $this->addError($attribute, 'Ты пидор');
        }
        
        return !count($this->getErrors($attribute));
    }
    
    public function getSubscribitionsModels()
    {
        return $this->hasMany(TwitterSubscribe::className(), ['followerId' => 'userId']);
    }
    
    public function getSubscribersModels()
    {
        return $this->hasMany(TwitterSubscribe::className(), ['userId' => 'userId']);
    }
    
    public function getSubscribitions()
    {
        return $this->hasMany(TwitterProfile::className(), ['userId' => 'userId'])
                ->via('subscribitionsModels');
    }
    
    public function getSubscribers()
    {
        return $this->hasMany(TwitterProfile::className(), ['followerId' => 'userId'])
                ->via('subscribersModels');
    }
    
    public function getFeed()
    {
        return $this->hasMany(Tweet::className(), ['userId' => 'userId'])
                ->via('subscribitions')
                ->where(['dateDeleted' => NULL])
                ->orderBy(['dateCreated' => SORT_DESC]);
    }
    
}
