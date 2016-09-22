<?php

namespace app\controllers;

use app\components\MyController,
    yii\helpers\ArrayHelper;

/**
 * 
 */
class ElectionsController extends MyController
{
    
    public function actionIndex()
    {
        $elections = [];
        foreach ($this->user->states as $state) {
            foreach ($state->agencies as $agency) {
                $elections[] = $agency->getNextElection();
                foreach ($agency->posts as $post) {
                    $elections[] = $post->getNextElection();
                }
            }
        }
        $elections = array_filter($elections);
        
        return $this->render('list', [
            'user' => $this->user,
            'elections' => $elections
        ]);
    }
    
}
