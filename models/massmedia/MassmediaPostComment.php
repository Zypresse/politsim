<?php

namespace app\models\massmedia;

use Yii,
    app\components\MyModel,
    app\models\massmedia\MassmediaPost,
    app\models\User;

/**
 * This is the model class for table "massmedia_posts_comments".
 *
 * @property integer $userId
 * @property integer $massmediaPostId
 * @property string $text
 * @property integer $created
 * 
 * @property MassmediaPost $post
 * @property User $user
 */
class MassmediaPostComment extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'massmedia_posts_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'massmediaPostId', 'created'], 'integer'],
            [['userId', 'massmediaPostId', 'text'], 'required'],
            [['text'], 'string']
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
            'text' => 'Text',
            'created' => 'Created',
        ];
    }
    
    public function getPost()
    {
        return $this->hasOne(MassmediaPost::className(), array('id' => 'massmediaPostId'));
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), array('id' => 'userId'));
    }
    
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created = time();
        }
        return parent::beforeSave($insert);
    }
    
    public static function primaryKey()
    {
        return [
            'massmediaPostId',
            'userId',
            'created'
        ];
    }
}
