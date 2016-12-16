<?php

namespace app\models\politics\elections;

use app\models\politics\Agency,
    app\models\politics\AgencyPost,
    app\models\base\MyActiveRecord;

/**
 * Объект выборов
 *
 * @property integer $id
 * @property integer $protoId
 * @property integer $agencyId
 * @property integer $postId
 * @property boolean $isIndividual
 * @property boolean $isOnlyParty
 * @property integer $dateRegistrationStart
 * @property integer $dateVotingStart
 * @property integer $dateVotingEnd
 * @property string $results
 * 
 * @property ElectionRequestIndividual[] $requestsIndividual
 * @property ElectionRequestParty[] $requestsParty
 * @property Agency $agency
 * @property AgencyPost $post
 * 
 * @property integer $status
 * 
 */
class Election extends MyActiveRecord
{
    
    /**
     * выборы ещё не начались
     */
    const STATUS_NOT_STARTED = 0;
    
    /**
     * идёт регистрация на выборы
     */
    const STATUS_REGISTRATION = 1;
    
    /**
     * идёт голосование
     */
    const STATUS_VOTING = 2;
    
    /**
     * идёт подвод итогов выборов
     */
    const STATUS_CALCULATING = 3;
    
    /**
     * выборы окончены
     */
    const STATUS_ENDED = 4;
    
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
            [['protoId', 'isIndividual', 'isOnlyParty', 'dateRegistrationStart', 'dateVotingStart', 'dateVotingEnd'], 'required'],
            [['protoId', 'agencyId', 'postId', 'dateRegistrationStart', 'dateVotingStart', 'dateVotingEnd'], 'integer', 'min' => 0],
            [['results'], 'string'],
            [['isIndividual', 'isOnlyParty'], 'boolean'],
            [['agencyId'], 'exist', 'skipOnError' => true, 'targetClass' => Agency::className(), 'targetAttribute' => ['agencyId' => 'id']],
        ];
    }
    
    public function getPost()
    {
        return $this->hasOne(AgencyPost::className(), ['id' => 'postId']);
    }
    
    public function getAgency()
    {
        return $this->hasOne(Agency::className(), ['id' => 'agencyId']);
    }
    
    public function getRequestsIndividual()
    {
        return $this->hasMany(ElectionRequestIndividual::className(), ['electionId' => 'id']);
    }
    
    public function getRequestsParty()
    {
        return $this->hasMany(ElectionRequestParty::className(), ['electionId' => 'id']);
    }
    
    public function getStatus()
    {
        if ($this->results) {
            return static::STATUS_ENDED;
        }
        $time = time();
        if ($time < $this->dateRegistrationStart) {
            return static::STATUS_NOT_STARTED;
        } elseif ($time < $this->dateVotingStart) {
            return static::STATUS_REGISTRATION;
        } elseif ($time < $this->dateVotingEnd) {
            return static::STATUS_VOTING;
        } else {
            return static::STATUS_CALCULATING;
        }
    }
    
}
