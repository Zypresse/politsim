<?php

namespace app\controllers;

use app\components\MyController,
    app\models\politics\elections\Election,
    yii\web\NotFoundHttpException,
    Yii;

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
            'elections' => $elections,
        ]);
    }
    
    public function actionSendRequestForm($id)
    {
        $election = $this->getElection($id);
        
        return $this->render('request-form', [
            'election' => $election,
            'user' => $this->user,
        ]);
    }
    
    private function getElection($id)
    {
        $election = Election::findByPk($id);
        if (is_null($election)) {
            Yii::$app->response->format = 'json';
            throw new NotFoundHttpException();
        }
        return $election;
    }
    
}
