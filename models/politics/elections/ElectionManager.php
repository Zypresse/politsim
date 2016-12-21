<?php

namespace app\models\politics\elections;

use app\models\politics\AgencyPost,
    app\models\politics\constitution\articles\postsonly\DestignationType,
    app\models\politics\constitution\ConstitutionArticleType;

/**
 * 
 */
abstract class ElectionManager
{
    
    const DAY_LENGTH = 60*60*24;
    
    /**
     * 
     * @param AgencyPost $post
     * @return Election
     */
    public static function createPostElection(AgencyPost &$post)
    {
        
        /* @var $article DestignationType */
        $destignationTypeArticle = $post->constitution->getArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE);
        $type = (int) $destignationTypeArticle->value;
        $whoId = (int) $destignationTypeArticle->value2;
        $settings = (int) $destignationTypeArticle->value3;
        $termsOfOfficeArticle = $post->constitution->getArticleByType(ConstitutionArticleType::TERMS_OF_OFFICE);
        $officeDays = (int) $termsOfOfficeArticle->value;
        $termsOfElectionArticle = $post->constitution->getArticleByType(ConstitutionArticleType::TERMS_OF_ELECTION);
        $registrationDays = (int) $termsOfElectionArticle->value;
        $waitingDays = (int) $termsOfElectionArticle->value2;
        $electionDays = (int) $termsOfElectionArticle->value3;
        
        /* @var $lastElection Election */
        $lastElection = $post->getElections()->orderBy(['id' => SORT_DESC])->one();
        if (is_null($lastElection)) {
            $start = time();
        } else {
            if ($lastElection->status != ElectionStatus::ENDED) {
                return $lastElection;
            }
            $start = ((int) $lastElection->dateVotingEnd) + $officeDays*static::DAY_LENGTH;
        }
        
        $election = new Election([
            'whomType' => ElectionWhomType::POST,
            'whomId' => $post->id,
            'settings' => $settings,
            'dateRegistrationStart' => $start,
            'dateRegistrationEnd' => $start+$registrationDays*static::DAY_LENGTH,
            'dateVotingStart' => $start+$registrationDays*static::DAY_LENGTH+$waitingDays*static::DAY_LENGTH,
            'dateVotingEnd' => $start+$registrationDays*static::DAY_LENGTH+$waitingDays*static::DAY_LENGTH+$electionDays*static::DAY_LENGTH,
        ]);
        
        switch ($type){
            case DestignationType::BY_STATE_ELECTION:
                $election->whoType = ElectionWhoType::STATE;
                $election->whoId = $post->stateId;
                break;
            case DestignationType::BY_DISTRICT_ELECTION:
                $election->whoType = ElectionWhoType::ELECTORAL_DISTRICT;
                $election->whoId = $whoId;
                break;
            case DestignationType::BY_AGENCY_ELECTION:
                $election->whoType = ElectionWhoType::AGENCY_MEMBERS;
                $election->whoId = $whoId;
                break;
            default:
                return null;
        }
        
        $election->save();
        return $election;
    }
    
    
}
