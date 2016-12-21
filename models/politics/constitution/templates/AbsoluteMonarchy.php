<?php

namespace app\models\politics\constitution\templates;

use yii\base\Exception,
    app\models\politics\State,
    app\models\politics\Agency,
    app\models\politics\AgencyPost,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\DestignationType;

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
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE, null, DestignationType::BY_PRECURSOR);
        
    }

}

