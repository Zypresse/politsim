<?php

namespace app\commands;

use yii\console\Controller,
    app\models\politics\elections\ElectionManager,
    app\models\politics\elections\ElectionStatus,
    app\models\politics\elections\ElectionWhomType,
    app\models\politics\AgencyPost,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\DestignationType;

/**
 * Calculate elect results
 *
 * Cron hourly
 */
class ElectionsController extends Controller
{

    public function actionIndex()
    {
        // посты у которых проводятся выборы
        /* @var $posts AgencyPost[] */
        $posts = AgencyPost::find()
                ->joinWith('articles')
                ->where(['type' => ConstitutionArticleType::DESTIGNATION_TYPE])
                ->andWhere(['in', 'value', [
                    DestignationType::BY_AGENCY_ELECTION,
                    DestignationType::BY_STATE_ELECTION,
                    DestignationType::BY_DISTRICT_ELECTION
                ]])
                ->all();
        
        foreach ($posts as $post) {
            $election = $post->getNextElection();
            switch ($election->status) {
                case ElectionStatus::CALCULATING:
                    ElectionManager::calculateResults($election);
                    break;
            }
        }
    }

}
