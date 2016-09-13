<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\Party,
    app\models\Membership;

/**
 * 
 */
class MembershipController extends MyController
{
    
    public function actionIndex()
    {
        return $this->render('list', [
            'approved' => $this->user->getApprovedMemberships()->with('party')->all(),
            'requested' => $this->user->getRequestedMemberships()->with('party')->all(),
            'user' => $this->user
        ]);
    }
    
    public function actionRequest($partyId)
    {
        $party = Party::findByPk($partyId);
        if (is_null($party)) {
            return $this->_r('Party not found');
        }
        
        $membership = new Membership([
            'userId' => $this->user->id,
            'partyId' => $party->id
        ]);
        
        // @TODO: принятие запросов в партию
        $membership->approve(false);
        
        if ($membership->save()) {
            $party->updateParams();
            return $this->_rOk();
        } else {
            return $this->_r($membership->getErrors());
        }
    }
    
    public function actionCancel($partyId)
    {
        $party = Party::findByPk($partyId);
        if (is_null($party)) {
            return $this->_r('Party not found');
        }
        
        /* @var $membership Membership */
        $membership = Membership::find()->where(['partyId' => $party->id, 'userId' => $this->user->id])->one();
        if (is_null($membership)) {
            return $this->_r('Membership not found');
        }
        
        if ($membership->fireSelf()) {
            $party->updateParams();
            return $this->_rOk();
        } else {
            return $this->_r($membership->getErrors());            
        }
        
    }
    
}
