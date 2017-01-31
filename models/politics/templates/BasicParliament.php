<?php

namespace app\models\politics\templates;

use Yii,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\DestignationType,
    app\models\politics\constitution\articles\postsonly\Powers,
    app\models\politics\constitution\articles\postsonly\powers\Bills,
    app\models\politics\Agency,
    app\models\politics\AgencyPost,
    app\models\politics\State;

/**
 * 
 */
final class BasicParliament implements AgencyTemplateInterface
{
        
    /**
     * 
     * @param integer $stateId
     * @return Agency
     */
    public static function create(int $stateId, $params)
    {
        $state = State::findByPk($stateId);
        $agency = new Agency([
            'stateId' => $stateId,
            'name' => $params['name'],
            'nameShort' => $params['nameShort'],
        ]);
        $agency->save();
        
        $leaderPost = new AgencyPost([
            'stateId' => $stateId,
            'name' => Yii::t('app/agencies', 'Parliament speaker of «{0}»', [
                $state->name,
            ]),
            'nameShort' => Yii::t('app/agencies', 'PS{0}', [
                $state->nameShort,
            ]),
        ]);
        $leaderPost->save();
        $leaderPost->link('agencies', $agency);
        
        $agency->constitution->setArticleByType(ConstitutionArticleType::LEADER_POST, null, $leaderPost->id);
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE, null, DestignationType::BY_AGENCY_ELECTION, $agency->id);
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::TERMS_OF_OFFICE, null, 30);
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::TERMS_OF_ELECTION, null, 7, 1, 1);
        $leaderPost->constitution->setArticleByType(ConstitutionArticleType::POWERS, Powers::BILLS, Bills::CREATE | Bills::VOTE | Bills::DISCUSS);
        
        foreach ($state->districts as $district)
        {
            $post = new AgencyPost([
                'stateId' => $stateId,
                'name' => Yii::t('app/agencies', 'Parliamentarian of «{0}»', [
                    $state->name,
                ]),
                'nameShort' => Yii::t('app/agencies', 'P{0}', [
                    $state->nameShort,
                ]),
            ]);
            $post->save();
            $post->link('agencies', $agency);
            
            $post->constitution->setArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE, null, DestignationType::BY_DISTRICT_ELECTION, $district->id);
            $post->constitution->setArticleByType(ConstitutionArticleType::TERMS_OF_OFFICE, null, 30);
            $post->constitution->setArticleByType(ConstitutionArticleType::TERMS_OF_ELECTION, null, 7, 1, 1);
            $post->constitution->setArticleByType(ConstitutionArticleType::POWERS, Powers::BILLS, Bills::CREATE | Bills::VOTE | Bills::DISCUSS);
        }
        
        return $agency;
        
    }

}
