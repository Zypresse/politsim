<?php

namespace app\controllers;

use Yii,
    app\components\MyController;

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
    
}
