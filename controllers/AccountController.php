<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\models\auth\Account;

/**
 * Description of AccountController
 *
 * @author ilya
 */
class AccountController extends Controller
{
    
    public $layout = 'app';
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
	return [
	    'access' => [
		'class' => AccessControl::className(),
		'rules' => [
		    [
			'allow' => true,
			'roles' => ['@'],
		    ],
		],
	    ],
	];
    }
    
    /**
     * 
     * @param integer $id
     * @return mixed
     */
    public function actionProfile(int $id)
    {
        return $this->render('profile', [
            'model' => $this->getModel($id),
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
