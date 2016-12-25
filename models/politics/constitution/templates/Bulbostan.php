<?php

namespace app\models\politics\constitution\templates;

use yii\base\Exception,
    app\models\politics\State,
    app\models\politics\Agency,
    app\models\politics\AgencyPost,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\DestignationType,
    app\models\politics\constitution\articles\statesonly\Parties;

/**
 * Тестовый шаблон для Беларуси
 */
class Bulbostan extends ConstitutionTemplate
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
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE, null, DestignationType::BY_STATE_ELECTION, null, DestignationType::SECOND_TOUR);
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::TERMS_OF_OFFICE, null, 30);
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::TERMS_OF_ELECTION, null, 5, 1, 1);
        $executive->constitution->setArticleByType(ConstitutionArticleType::LEADER_POST, null, $leaderPost->id);
    }

}
