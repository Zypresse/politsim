<?php

namespace app\models\massmedia;

use Yii,
    app\components\MyModel,
    app\models\massmedia\MassmediaPost;

/**
 * This is the model class for table "massmedia_posts_votes".
 *
 * @property integer $userId
 * @property integer $massmediaPostId
 * @property integer $direction
 * 
 * @property User $user
 * @property MassmediaPost $post 
 */
class MassmediaPostVote extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'massmedia_posts_votes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'massmediaPostId', 'direction'], 'integer'],
            [['userId', 'massmediaPostId'], 'unique', 'targetAttribute' => ['userId', 'massmediaPostId'], 'message' => 'The combination of User ID and Massmedia Post ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'User ID',
            'massmediaPostId' => 'Massmedia Post ID',
            'direction' => 'Direction',
        ];
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), array('id' => 'userId'));
    }
    
    public function getPost()
    {
        return $this->hasOne(MassmediaPost::className(), array('id' => 'massmediaPostId'));
    }
}
