<?php

namespace app\models\politics\constitution\templates;

use yii\base\Exception,
    app\models\politics\State,
    app\models\politics\Agency,
    app\models\politics\AgencyPost,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\DestignationType,
    app\models\politics\constitution\articles\statesonly\Parties,
    app\models\politics\constitution\articles\postsonly\Powers,
    app\models\politics\constitution\articles\postsonly\powers\Bills,
    app\models\politics\constitution\articles\postsonly\powers\Parties as PowersParties;

/**
 * 
 */
class AbsoluteMonarchy extends ConstitutionTemplate
{
    
    private static function validateParams($params)
    {
        if (!isset($params['leaderPost']) || !($params['leaderPost'] instanceof AgencyPost)) {
            throw new Exception("AbsoluteMonarchy requires param 'leaderPost'");
        }
        if (!isset($params['executive']) || !($params['executive'] instanceof Agency)) {
            throw new Exception("AbsoluteMonarchy requires param 'executive'");
        }
    }
    
    public static function generate(State &$state, $params = [])
    {
        static::validateParams($params);
        /* @var $leaderPost AgencyPost */
        $leaderPost = $params['leaderPost'];
        /* @var $executive Agency */
        $executive = $params['executive'];
        
        $state->constitution->setArticleByType(ConstitutionArticleType::LEADER_POST, null, $leaderPost->id);
        $state->constitution->setArticleByType(ConstitutionArticleType::MULTIPOST, null, false);
        $state->constitution->setArticleByType(ConstitutionArticleType::PARTIES, null, Parties::FORBIDDEN);
        $state->constitution->setArticleByType(ConstitutionArticleType::BILLS, null, 0);
        $state->constitution->setArticleByType(ConstitutionArticleType::BUISNESS, null, 1);
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE, null, DestignationType::BY_PRECURSOR);
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::TERMS_OF_OFFICE, null, 0);
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::TERMS_OF_ELECTION, null, 0);
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::POWERS, Powers::BILLS, Bills::VOTE | Bills::CREATE | Bills::ACCEPT | Bills::VETO | Bills::DISCUSS);
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::POWERS, Powers::PARTIES, PowersParties::ACCEPT | PowersParties::REVOKE);
        $executive->constitution->setArticleByType(ConstitutionArticleType::LEADER_POST, null, $leaderPost->id);
    }

}

