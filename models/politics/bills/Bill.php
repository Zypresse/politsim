<?php

namespace app\models\politics\bills;

use Yii,
    app\models\User,
    app\models\politics\State,
    app\models\politics\AgencyPost,
    app\models\base\MyActiveRecord;

/**
 * Законопроект
 *
 * @property integer $id
 * @property integer $protoId
 * @property integer $stateId
 * @property integer $userId
 * @property integer $postId
 * @property integer $dateCreated
 * @property integer $dateApproved
 * @property integer $vetoPostId
 * @property boolean $isDictatorBill
 * @property integer $votesPlus
 * @property integer $votesMinus
 * @property integer $votesAbstain
 * @property string $data
 * 
 * @property User $user
 * @property State $state
 * @property AgencyPost $post
 * @property AgencyPost $vetoPost
 * @property BillVote[] $votes
 * @property BillProto $proto
 * 
 */
class Bill extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bills';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['protoId', 'stateId', 'dateCreated'], 'required'],
            [['protoId', 'stateId', 'userId', 'postId', 'dateCreated', 'dateApproved', 'vetoPostId', 'votesPlus', 'votesMinus', 'votesAbstain'], 'integer', 'min' => 0],
            [['data'], 'string'],
            [['isDictatorBill'], 'boolean'],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
            [['stateId'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['stateId' => 'id']],
            [['postId'], 'exist', 'skipOnError' => true, 'targetClass' => AgencyPost::className(), 'targetAttribute' => ['postId' => 'id']],
            [['vetoPostId'], 'exist', 'skipOnError' => true, 'targetClass' => AgencyPost::className(), 'targetAttribute' => ['vetoPostId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'protoId' => Yii::t('app', 'Proto ID'),
            'stateId' => Yii::t('app', 'State ID'),
            'userId' => Yii::t('app', 'User ID'),
            'postId' => Yii::t('app', 'Post ID'),
            'dateCreated' => Yii::t('app', 'Date Created'),
            'dateApproved' => Yii::t('app', 'Date Approved'),
            'vetoPostId' => Yii::t('app', 'Veto Post ID'),
            'isDictatorBill' => Yii::t('app', 'Is Dictator Bill'),
            'votesPlus' => Yii::t('app', 'Votes Plus'),
            'votesMinus' => Yii::t('app', 'Votes Minus'),
            'votesAbstain' => Yii::t('app', 'Votes Abstain'),
            'data' => Yii::t('app', 'Data'),
        ];
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
    
    public function getPost()
    {
        return $this->hasOne(AgencyPost::className(), ['id' => 'postId']);
    }
    
    public function getVetoPost()
    {
        return $this->hasOne(AgencyPost::className(), ['id' => 'vetoPostId']);
    }
    
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'stateId']);
    }
    
    public function getVotes()
    {
        return $this->hasMany(BillVote::className(), ['billId' => 'id']);
    }
    
    private $_proto = null;
    
    public function getProto()
    {
        if (is_null($this->_proto)) {
            $this->_proto = BillProto::instantiate($this->protoId);
        }
        return $this->_proto;
    }
    
}
