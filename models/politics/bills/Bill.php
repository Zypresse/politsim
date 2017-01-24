<?php

namespace app\models\politics\bills;

use Yii,
    yii\behaviors\TimestampBehavior,
    app\models\User,
    app\models\politics\State,
    app\models\politics\AgencyPost,
    app\models\base\MyActiveRecord,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\Powers,
    app\models\Message,
    app\models\MessageType;

/**
 * Законопроект
 *
 * @property integer $id
 * @property integer $protoId
 * @property integer $stateId
 * @property integer $userId
 * @property integer $postId
 * @property integer $dateCreated
 * @property integer $dateVotingFinished
 * @property integer $dateFinished
 * @property integer $isApproved
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
 * @property User[] $voters
 * @property AgencyPost[] $votersPosts
 * @property Message[] $messages
 * 
 * @property boolean $isFinished
 * 
 */
class Bill extends MyActiveRecord
{
    
    public $dataArray = null;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bills';
    }

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
    public function rules()
    {
        return [
            [['protoId', 'stateId'], 'required'],
            [['protoId', 'stateId', 'userId', 'postId', 'dateCreated', 'dateVotingFinished', 'dateFinished', 'vetoPostId', 'votesPlus', 'votesMinus', 'votesAbstain'], 'integer', 'min' => 0],
            [['data'], 'string'],
            [['isDictatorBill', 'isApproved'], 'boolean'],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
            [['stateId'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['stateId' => 'id']],
            [['postId'], 'exist', 'skipOnError' => true, 'targetClass' => AgencyPost::className(), 'targetAttribute' => ['postId' => 'id']],
            [['vetoPostId'], 'exist', 'skipOnError' => true, 'targetClass' => AgencyPost::className(), 'targetAttribute' => ['vetoPostId' => 'id']],
            [['dataArray'], 'validateData'],
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
            'dateVotingFinished' => Yii::t('app', 'Date Voting Finished'),
            'dateFinished' => Yii::t('app', 'Date Finished'),
            'isApproved' => Yii::t('app', 'Is Approved'),
            'vetoPostId' => Yii::t('app', 'Veto Post ID'),
            'isDictatorBill' => Yii::t('app', 'Is Dictator Bill'),
            'votesPlus' => Yii::t('app', 'Votes Plus'),
            'votesMinus' => Yii::t('app', 'Votes Minus'),
            'votesAbstain' => Yii::t('app', 'Votes Abstain'),
            'data' => Yii::t('app', 'Data'),
            'dataArray' => Yii::t('app', 'State name'),
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
    
    public function isAllreadyVoted(int $postId)
    {
        return $this->getVotes()->where(['postId' => $postId])->exists();
    }
    
    public function getVoters()
    {
        return $this->hasMany(User::className(), ['id' => 'userId'])
                ->via('votersPosts');
    }
    
    public function getVotersPosts()
    {
        return $this->hasMany(AgencyPost::className(), ['stateId' => 'stateId'])
                ->joinWith('articles')
                ->where(['constitutionsArticles.type' => ConstitutionArticleType::POWERS, 'constitutionsArticles.subType' => Powers::BILLS])
                ->andWhere(['>', 'constitutionsArticles.value', 0]);
    }
    
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['recipientId' => 'id'])
                ->where(['typeId' => MessageType::BILL_DISQUSSION]);
    }


    private $_proto = null;
    
    public function getProto()
    {
        if (is_null($this->_proto)) {
            $this->_proto = BillProto::instantiate($this->protoId);
        }
        return $this->_proto;
    }
    
    /**
     * @param string $attribute the attribute currently being validated
     * @param mixed $params the value of the "params" given in the rule
     */
    public function validateData($attribute, $params)
    {
        return $this->proto->validate($this);
    }
    
    public function beforeSave($insert)
    {
        $this->data = json_encode($this->dataArray);
        if ($insert) {
            $article = $this->state->constitution->getArticleByType(ConstitutionArticleType::BILLS);
            $this->dateVotingFinished = time() + $article->value * 60 * 60;
        }
        return parent::beforeSave($insert);
    }
            
    /**
     * 
     * @return boolean
     */
    public function accept() : bool
    {
        $this->isApproved = true;
        $this->dateFinished = time();
        return $this->save() && $this->proto->accept($this);
    }
    
    /**
     * 
     * @return boolean
     */
    public function decline(): bool
    {
        $this->isApproved = false;
        $this->dateFinished = time();
        return $this->save();
    }
    
    /**
     * 
     * @return boolean
     */
    public function veto(int $postId): bool
    {
        $this->vetoPostId = $postId;
        return $this->decline();
    }
    
    public static function instantiate($row)
    {
        $model = parent::instantiate($row);
        $model->dataArray = json_decode($row['data'], true);
        return $model;
    }
    
    /**
     * Отображает название и суть законопроекта
     */
    public function render() : string
    {
        return $this->proto->render($this);
    }
    
    /**
     * Отображает суть законопроекта
     */
    public function renderFull() : string
    {
        return $this->proto->renderFull($this);
    }
    
    /**
     * 
     * @param integer $variant
     * @param boolean $save
     */
    public function addVote(int $variant, $save = true)
    {
        switch ($variant) {
            case BillVote::VARIANT_PLUS:
                $this->votesPlus++;
                break;
            case BillVote::VARIANT_MINUS:
                $this->votesMinus++;
                break;
            case BillVote::VARIANT_ABSTAIN:
                $this->votesAbstain++;
                break;
        }
        if ($save) {
            $this->save();
        }
    }
    
    public function getIsFinished()
    {
        return !!$this->dateFinished;
    }
    
}
