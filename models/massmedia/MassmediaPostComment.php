<?php

namespace app\models\massmedia;

use Yii,
    app\components\MyModel,
    app\models\massmedia\MassmediaPost;

/**
 * This is the model class for table "massmedia_posts_comments".
 *
 * @property integer $userId
 * @property integer $massmediaPostId
 * @property string $text
 * @property integer $created
 * 
 * @property MassmediaPost $post
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
            [['text', 'created'], 'required'],
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
}
