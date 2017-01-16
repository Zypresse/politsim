<?php

namespace app\models\politics\constitution\templates;

use yii\base\Exception,
    app\models\politics\State,
    app\models\politics\Region,
    app\models\politics\City,
    app\models\politics\Agency,
    app\models\politics\AgencyPost,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\DestignationType,
    app\models\politics\constitution\articles\statesonly\Parties,
    app\models\politics\constitution\articles\postsonly\Powers,
    app\models\politics\constitution\articles\postsonly\Bills;

/**
 * Тестовый шаблон для Беларуси
 */
class Bulbostan extends ConstitutionTemplate
{
    
    private static function validateParams($params)
    {
        if (!isset($params['leaderPost']) || !($params['leaderPost'] instanceof AgencyPost)) {
            throw new Exception("Bulbostan requires param 'leaderPost'");
        }
        if (!isset($params['executive']) || !($params['executive'] instanceof Agency)) {
            throw new Exception("Bulbostan requires param 'executive'");
        }
        if (!isset($params['gouvernors']) || !is_array($params['gouvernors'])) {
            throw new Exception("Bulbostan requires param 'gouvernors'");
        }
        if (!isset($params['majors']) || !is_array($params['majors'])) {
            throw new Exception("Bulbostan requires param 'cityIdToMajors'");
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
        $state->constitution->setArticleByType(ConstitutionArticleType::BILLS, null, 24);
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE, null, DestignationType::BY_STATE_ELECTION, null, DestignationType::SECOND_TOUR+DestignationType::NONE_OF_THE_ABOVE);
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::TERMS_OF_OFFICE, null, 30);
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::TERMS_OF_ELECTION, null, 5, 1, 1);
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::POWERS, Powers::BILLS, Bills::VOTE | Bills::CREATE | Bills::ACCEPT | Bills::VETO | Bills::DISCUSS);
        $executive->constitution->setArticleByType(ConstitutionArticleType::LEADER_POST, null, $leaderPost->id);
        
        foreach ($params['gouvernors'] as $g) {
            /* @var $gouvernor AgencyPost */
            /* @var $region Region */
            list($gouvernor, $region) = $g;
            $gouvernor->constitution->setArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE, null, DestignationType::BY_OTHER_POST, $leaderPost->id);
            $gouvernor->constitution->setArticleByType(ConstitutionArticleType::TERMS_OF_OFFICE, null, 30);
            $gouvernor->constitution->setArticleByType(ConstitutionArticleType::TERMS_OF_ELECTION, null, 5, 1, 1);
            $region->constitution->setArticleByType(ConstitutionArticleType::LEADER_POST, null, $gouvernor->id);
        }
        
        foreach ($params['majors'] as $c) {
            /* @var $major AgencyPost */
            /* @var $city City */
            list($major, $city) = $c;
            $major->constitution->setArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE, null, DestignationType::BY_CITY_ELECTION, $city->id);
            $major->constitution->setArticleByType(ConstitutionArticleType::TERMS_OF_OFFICE, null, 30);
            $major->constitution->setArticleByType(ConstitutionArticleType::TERMS_OF_ELECTION, null, 5, 1, 1);
            $city->constitution->setArticleByType(ConstitutionArticleType::LEADER_POST, null, $major->id);
        }
    }

}
