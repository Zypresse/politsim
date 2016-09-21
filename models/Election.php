<?php

namespace app\models;

use Yii,
    app\components\MyModel;

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
 */
class Election extends MyModel
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
            [['protoId', 'isIndividual', 'isOnlyParty', 'dateRegistrationStart', 'dateVotingStart', 'dateVotingEnd'], 'required'],
            [['protoId', 'agencyId', 'postId', 'dateRegistrationStart', 'dateVotingStart', 'dateVotingEnd'], 'integer', 'min' => 0],
            [['results'], 'string'],
            [['isIndividual', 'isOnlyParty'], 'boolean'],
            [['agencyId'], 'exist', 'skipOnError' => true, 'targetClass' => Agency::className(), 'targetAttribute' => ['agencyId' => 'id']],
        ];
    }
    
    public function getAgencyPost()
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
    
}
