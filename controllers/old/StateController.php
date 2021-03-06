<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    app\controllers\base\MyController,
    app\models\politics\State,
    app\models\politics\Agency,
    app\models\politics\LicenseRule;

/**
 * 
 */
final class StateController extends MyController
{
    
    public function actionIndex(int $id)
    {
        
        $state = $this->getState($id);
                
        return $this->render('view', [
            'state' => $state,
            'user' => $this->user
        ]);
    }
    
    public function actionBills(int $id)
    {
        
        $state = $this->getState($id);
                
        return $this->render('bills', [
            'state' => $state,
            'billsActive' => $state->getBillsActive()->with('post')->with('user')->orderBy(['dateCreated' => SORT_DESC])->all(),
            'billsFinished' => $state->getBillsFinished()->with('post')->with('user')->orderBy(['dateFinished' => SORT_DESC])->all(),
            'user' => $this->user
        ]);
    }
    
    public function actionConstitution(int $id)
    {
        
        $state = $this->getState($id);
                        
        return $this->render('constitution', [
            'state' => $state,
            'user' => $this->user
        ]);
    }
    
    public function actionAgency($id)
    {
        
        $agency = Agency::findByPk($id);
        if (is_null($agency)) {
            return $this->_r(Yii::t('app', 'Agency not found'));
        }
        
        return $this->render('agency', [
            'agency' => $agency,
            'user' => $this->user
        ]);
    }
    
    public function actionLicenseRuleInfo(int $id, int $protoId)
    {
        $state = $this->getState($id);
        
        $rule = LicenseRule::findOne([
            'stateId' => $state->id,
            'protoId' => $protoId,
        ]);
        if (is_null($rule)) {
            $this->result = null;
        } else {
            $this->result = $rule->getPublicAttributes();
        }
        return $this->_r();
    }
    
    public function actionRegions(int $id)
    {
        $state = $this->getState($id);
        $this->result = [];
        foreach ($state->regions as $region) {
            $this->result[] = $region->getPublicAttributes();
        }
        return $this->_r();
    }


    private function getState(int $id)
    {
        $state = State::findByPk($id);
        if (is_null($state)) {
            throw new NotFoundHttpException(Yii::t('app', 'State not found'));
        }
        return $state;
    }
    
}
