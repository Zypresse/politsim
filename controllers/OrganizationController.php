<?php

namespace app\controllers;

use Yii;
use app\controllers\base\AppController;
use app\models\politics\Organization;
use app\models\politics\OrganizationMembership;

/**
 * Description of OrganizationController
 *
 * @author ilya
 */
class OrganizationController extends AppController
{
    
    public function actionIndex()
    {
        return $this->render('index', [
            'approved' => OrganizationMembership::findByUserId($this->user->id)->andWhere(['is not', 'dateApproved', null])->with('org')->all(),
            'requested' => OrganizationMembership::findByUserId($this->user->id)->andWhere(['dateApproved' => null])->with('org')->all(),
            'user' => $this->user,
        ]);
    }
    
}
