<?php

namespace app\controllers;

use app\controllers\base\AppController;
use app\models\government\State;
use app\models\government\Citizenship;
use yii\web\NotFoundHttpException;

/**
 * 
 */
final class CitizenshipController extends AppController
{

    public function actionIndex()
    {
        return $this->render('index', [
            'approved' => Citizenship::findByUser($this->user->id)->andWhere(['is not', 'dateApproved', null])->with('state')->all(),
            'requested' => Citizenship::findByUser($this->user->id)->andWhere(['dateApproved' => null])->with('state')->all(),
            'user' => $this->user,
        ]);
    }

    public function actionRequest(int $stateId)
    {
        $state = $this->findState($stateId);
        $citizenship = new Citizenship([
            'userId' => $this->user->id,
            'stateId' => $state->id,
        ]);

        // @TODO: принятие запросов на гражданство

        if ($citizenship->approve()) {
            return $this->ok();
        } else {
            return $this->error($citizenship->getErrors());
        }
    }

    public function actionCancel(int $stateId)
    {

        $citizenship = $this->findCitizenship($stateId, $this->user->id);
        if ($citizenship->fireSelf()) {
            return $this->ok();
        } else {
            return $this->error($citizenship->getErrors());
        }
    }

    /**
     * 
     * @param integer $stateId
     * @return State
     */
    private function findState(int $stateId)
    {
        $state = State::findOne($stateId);
        if (is_null($state)) {
            throw new NotFoundHttpException("State not found");
        }
        return $state;
    }

    /**
     * 
     * @param integer $stateId
     * @param integer $userId
     * @return Citizenship
     */
    private function findCitizenship(int $stateId, int $userId)
    {
        $citizenship = Citizenship::findOne(['stateId' => $stateId, 'userId' => $userId]);
        if (is_null($citizenship)) {
            throw new NotFoundHttpException("Citizenship not found");
        }
        return $citizenship;
    }

}
