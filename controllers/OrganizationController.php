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
    
    public function actionCreate()
    {
        $model = new Organization([
            'leaderId' => $this->user->id,
        ]);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = $model->getDb()->beginTransaction();
            if ($model->save() && $model->saveFlag()) {
                $membership = new OrganizationMembership([
                    'orgId' => $model->id,
                    'userId' => $this->user->id,
                ]);
                if (!$membership->approve()) {
                    var_dump($membership->getErrors()); die();
                }
                $transaction->commit();
                return $this->redirect(['organization/profile', 'id' => $model->id]);
            } else {
                $transaction->rollBack();
            }
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
}
