<?php

namespace app\models\politics\constitution\templates;

use yii\base\Exception,
    app\models\politics\State,
    app\models\politics\Agency,
    app\models\politics\AgencyPost,
    app\models\politics\constitution\ConstitutionArticleType;

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
        $leaderPost = $params['leaderPost'];
        $executive = $params['executive'];
        
        $constitution = $state->constitution;
        $constitution->setArticleByType(ConstitutionArticleType::LEADER_POST, null, $leaderPost->id);
        
    }

}
