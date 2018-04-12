<?php

namespace app\controllers;

use Yii;
use app\controllers\base\AppController;
use yii\web\NotFoundHttpException;
use app\models\auth\Account;

/**
 * Description of AccountController
 *
 * @author ilya
 */
class AccountController extends AppController
{
    
    /**
     * 
     * @return mixed
     */
    public function actionProfile()
    {
        return $this->render('profile', [
            'model' => $this->getModel(Yii::$app->user->id),
        ]);
    }
    
    /**
     * 
     * @param integer $id
     * @return Account
     * @throws NotFoundHttpException
     */
    private function getModel(int $id): Account
    {
        $model = Account::findIdentity($id);
        if (is_null($model)) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
    
}
