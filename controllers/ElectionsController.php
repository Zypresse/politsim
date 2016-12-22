<?php

namespace app\controllers;

use app\components\MyController,
    app\models\politics\elections\Election,
    app\models\politics\State,
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
            foreach ($state->posts as $post) {
                $elections[] = $post->getNextElection();
            }
        }
        
        return $this->render('list', [
            'user' => $this->user,
            'elections' => $elections,
        ]);
    }
    
    public function actionState($id)
    {
        $state = State::findByPk($id);
        if (is_null($state)) {
            return $this->_r(Yii::t('app', 'State not found'));
        }
        
        $electionsNew = [];
        $electionsAll = [];
        foreach ($state->posts as $post) {
            $electionsNew[] = $post->getNextElection();
            $electionsAll = array_merge($electionsAll, $post->getElections()->where(['<', 'dateVotingEnd', time()])->orderBy(['dateVotingEnd' => SORT_DESC])->all());
        }
        
        return $this->render('state-list', [
            'user' => $this->user,
            'state' => $state,
            'new' => $electionsNew,
            'all' => $electionsAll,
        ]);
    }


    public function actionSendRequestForm($id)
    {
        $election = $this->getElection($id);
        if (!$election->canSendRequest($this->user)) {
            return $this->_r(Yii::t('app', 'You can not make request for this election'));
        }
        
        return $this->render('request-form');
    }
    
    public function actionSendRequest($id)
    {
        $election = $this->getElection($id);
        if (!$election->canSendRequest($this->user)) {
            return $this->_r(Yii::t('app', 'You can not make request for this election'));
        }
        
        if ($election->sendUserRequest($this->user)) {
            return $this->_rOk();
        } else {
            return $this->_r(Yii::t('app', 'Unknown error'));
        }
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
