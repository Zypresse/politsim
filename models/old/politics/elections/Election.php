<?php

namespace app\models\politics\elections;

use Yii,
    app\models\User,
    app\models\base\MyActiveRecord;

/**
 * Объект выборов
 *
 * @property integer $id
 * @property integer $whomType const from ElectionWhomType Кого выбираем
 * @property integer $whomId id поста / агенства / референдума / ...
 * @property integer $whoType const from ElectionWhoType кто выбирает
 * @property integer $whoId id страны / округа / неба / аллаха
 * @property integer $settings bitmask с настройкам (второй тур, что-то ещё)
 * @property integer $initiatorElectionId связанные выборы (сюда ставится id первого тура у второго напр.)
 * @property integer $dateRegistrationStart
 * @property integer $dateRegistrationEnd
 * @property integer $dateVotingStart
 * @property integer $dateVotingEnd
 * @property string $results
 * 
 * @property Election $initiator
 * @property ElectionRequest[] $requests
 * @property ElectionOwner $whom
 * @property MyActiveRecord $who
 * @property ElectionVoteUser $votesByUsers
 * @property ElectionVotePop $votesByPops
 * 
 * @property integer $status
 * 
 */
class Election extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['whomType', 'whoType', 'settings', 'dateRegistrationStart', 'dateRegistrationEnd', 'dateVotingStart', 'dateVotingEnd'], 'required'],
            [['whomType', 'whomId', 'whoType', 'whoId', 'settings', 'initiatorElectionId', 'dateRegistrationStart', 'dateRegistrationEnd', 'dateVotingStart', 'dateVotingEnd'], 'integer', 'min' => 0],
            [['results'], 'string'],
            [['initiatorElectionId'], 'exist', 'skipOnError' => true, 'targetClass' => Election::className(), 'targetAttribute' => ['initiatorElectionId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'whomType' => Yii::t('app', 'Whom Type'),
            'whomId' => Yii::t('app', 'Whom ID'),
            'whoType' => Yii::t('app', 'Who Type'),
            'whoId' => Yii::t('app', 'Who ID'),
            'settings' => Yii::t('app', 'Settings'),
            'initiatorElectionId' => Yii::t('app', 'Initiator Election ID'),
            'dateRegistrationStart' => Yii::t('app', 'Date Registration Start'),
            'dateRegistrationEnd' => Yii::t('app', 'Date Registration End'),
            'dateVotingStart' => Yii::t('app', 'Date Voting Start'),
            'dateVotingEnd' => Yii::t('app', 'Date Voting End'),
            'results' => Yii::t('app', 'Results'),
        ];
    }
    
    public function getInitiator()
    {
        return $this->hasOne(Election::className(), ['id' => 'initiatorElectionId']);
    }
    
    public function getRequests()
    {
        return $this->hasMany(ElectionRequest::className(), ['electionId' => 'id']);
    }
    
    public function getVotesByUsers()
    {
        return $this->hasMany(ElectionVoteUser::className(), ['electionId' => 'id']);
    }
    
    public function getVotesByPops()
    {
        return $this->hasMany(ElectionVotePop::className(), ['electionId' => 'id']);
    }
    
    public function getWhom()
    {
        return $this->hasOne(ElectionWhomType::getClassByType($this->whomType), ['id' => 'whomId']);
    }
    
    public function getWho()
    {
        return $this->hasOne(ElectionWhoType::getClassByType($this->whoType), ['id' => 'whoId']);
    }
    
    public function canSendRequest(User &$user)
    {
        return $this->status == ElectionStatus::REGISTRATION
                && $user->isHaveCitizenship($this->whom->getTaxStateId())
                && !$this->getRequests()
                ->where([
                    'type' => ElectionRequestType::USER_SELF,
                    'objectId' => $user->id
                ])
                ->exists();
    }
    
    public function canVote(User &$user)
    {
        return $this->status == ElectionStatus::VOTING
                && ElectionWhoType::canVote($this->whoId, $this->whoType, $user)
                && !$this->getVotesByUsers()
                ->where([
                    'userId' => $user->id,
                ])
                ->exists();
    }
    
    /**
     * 
     * @param integer $type
     * @param integer $objectId
     * @return boolean
     */
    public function sendRequest($type, $objectId)
    {
        $request = new ElectionRequest([
            'type' => $type,
            'objectId' => $objectId,
            'electionId' => $this->id,
            'variant' => $this->getRequests()->count()+1,
        ]);
        return $request->save();
    }
    
    /**
     * 
     * @param User $user
     * @return boolean
     */
    public function sendUserRequest(User &$user)
    {
        if ($this->sendRequest(ElectionRequestType::USER_SELF, $user->id)) {
            Yii::$app->notificator->registeredForPostElections($user->id, $this->whom, true);
            return true;
        } else {
            return false;
        }
    }
    
    public function getStatus()
    {
        if ($this->results) {
            return ElectionStatus::ENDED;
        }
        
        $time = time();
        if ($time < $this->dateRegistrationStart) {
            return ElectionStatus::NOT_STARTED;
        } elseif ($time < $this->dateRegistrationEnd) {
            return ElectionStatus::REGISTRATION;
        } elseif ($time < $this->dateVotingStart) {
            return ElectionStatus::REGISTRATION_ENDED;
        } elseif ($time < $this->dateVotingEnd) {
            return ElectionStatus::VOTING;
        } else {
            return ElectionStatus::CALCULATING;
        }
    }
}
