<?php

namespace app\models\massmedia;

use Yii,
    app\components\MyModel,
    app\models\massmedia\Massmedia,
    app\models\massmedia\MassmediaPostComment,
    app\models\massmedia\MassmediaPostVote,
    app\models\User,
    app\models\events\Event,
    app\models\poprequests\PopRequest;

/**
 * This is the model class for table "massmedia_posts".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property integer $massmediaId
 * @property integer $authorId
 * @property integer $eventId
 * @property integer $popRequestId
 * @property integer $votesPlus
 * @property integer $votesMinus
 * @property integer $rating
 * @property integer $created
 * 
 * @property Massmedia $massmedia
 * @property User $author
 * @property Event $event
 * @property PopRequest $popRequest
 */
class MassmediaPost extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'massmedia_posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text', 'created'], 'required'],
            [['title', 'text'], 'string'],
            [['massmediaId', 'authorId', 'eventId', 'popRequestId', 'votesPlus', 'votesMinus', 'rating', 'created'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'text' => 'Text',
            'massmediaId' => 'Massmedia ID',
            'authorId' => 'Author ID',
            'eventId' => 'Event ID',
            'popRequestId' => 'Pop Request ID',
            'votesPlus' => 'Votes Plus',
            'votesMinus' => 'Votes Minus',
            'rating' => 'Rating',
            'created' => 'Created',
        ];
    }
    
    public function getMassmedia()
    {
        return $this->hasOne(Massmedia::className(), array('id' => 'massmediaId'));
    }
    
    public function getAuthor()
    {
        return $this->hasOne(User::className(), array('id' => 'authorId'));
    }
    
    public function getEvent()
    {
        return $this->hasOne(Event::className(), array('id' => 'eventId'));
    }
    
    public function getPopRequest()
    {
        return $this->hasOne(PopRequest::className(), array('id' => 'popRequestId'));
    }
    
    public function getComments()
    {
        return $this->hasMany(MassmediaPostComment::className(), array('massmediaPostId' => 'id'));
    }
    
    public function getVotes()
    {
        return $this->hasMany(MassmediaPostVote::className(), array('massmediaPostId' => 'id'));
    }
    
    public function isUserVoted(User $user)
    {
        return $this->getVotes()->where(['userId' => $user->id])->count() > 0;
    }
}
