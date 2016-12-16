<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\politics\Party,
    app\models\politics\PartyPost,
    app\models\politics\Membership;

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
            return $this->_r(Yii::t('app', 'Party not found'));
        }
        
        if ($party->joiningRules == Party::JOINING_RULES_PRIVATE) {
            return $this->_r(Yii::t('app', 'Access denied'));
        }
        
        $membership = new Membership([
            'userId' => $this->user->id,
            'partyId' => $party->id
        ]);
        
        if ($party->joiningRules == Party::JOINING_RULES_OPEN) {
            $membership->approve(false);
        }
        
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
            return $this->_r(Yii::t('app', 'Party not found'));
        }
        
        /* @var $membership Membership */
        $membership = Membership::find()->where(['partyId' => $party->id, 'userId' => $this->user->id])->one();
        if (is_null($membership)) {
            return $this->_r(Yii::t('app', 'Membership not found'));
        }
        
        if ($membership->fireSelf()) {
            $party->updateParams();
            return $this->_rOk();
        } else {
            return $this->_r($membership->getErrors());            
        }
        
    }
    
    public function actionAccept($userId, $partyId)
    {
        
        $party = Party::findByPk($partyId);
        if (is_null($party)) {
            return $this->_r(Yii::t('app', 'Party not found'));
        }
        
        $userPost = $party->getPostByUserId($this->user->id);
        
        if (!$userPost || !($userPost->powers & PartyPost::POWER_APPROVE_REQUESTS)) {
            return $this->_r(Yii::t('app', 'Access denied'));            
        }
        
        /* @var $membership Membership */
        $membership = Membership::find()->where(['partyId' => $party->id, 'userId' => $userId])->one();
        if (is_null($membership)) {
            return $this->_r(Yii::t('app', 'Membership not found'));
        }
        
        $membership->approve(false);
        
        if ($membership->save()) {
            $party->updateParams();
            return $this->_rOk();
        } else {
            return $this->_r($membership->getErrors());
        }
        
    }

    public function actionManageRequests($partyId)
    {
        $party = Party::findByPk($partyId);
        if (is_null($party)) {
            return $this->_r(Yii::t('app', 'Party not found'));
        }
        
        return $this->render('requests', [
            'party' => $party,
            'requests' => $party->getRequestedMemberships()->with('user')->orderBy(['dateCreated' => SORT_ASC])->all(),
            'user' => $this->user
        ]);
    }
    
}
