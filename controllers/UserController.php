<?php

namespace app\controllers;

use Yii;
use app\controllers\base\AppController;
use app\models\auth\User;
use app\models\auth\Account;
use yii\web\NotFoundHttpException;
use app\exceptions\NotAllowedHttpException;

/**
 * Description of UserController
 *
 * @author ilya
 */
class UserController extends AppController
{
    
    public function actionProfile(int $id = null)
    {
        if (!$id) {
            $id = $this->user->id;
        }
        return $this->render('profile', [
            'model' => $this->getModel($id),
            'viewer' => null, // viewer User
            'isOwner' => $this->user->id === $id,
        ]);
    }
    
    public function actionCreate()
    {
        $model = new User([
            'accountId' => Yii::$app->user->id,
        ]);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = $model->getDb()->beginTransaction();
            if ($model->save() && $model->saveAvatar()) {
                $model->account->activeUserId = $model->id;
                $model->account->save();
                $transaction->commit();
                return $this->redirect(['user/profile', 'id' => $model->id]);
            } else {
                $transaction->rollBack();
            }
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    public function actionSelect(int $id)
    {
        $model = $this->getModel($id);
        if ($model->accountId !== Yii::$app->user->id && Yii::$app->user->identity->role !== Account::ROLE_ADMIN) {
            throw new NotAllowedHttpException();
        }
        
        Yii::$app->user->identity->activeUserId = $id;
        Yii::$app->user->identity->save();
        return $this->redirect(["user/profile", "id" => $id]);
    }
    
    /**
     * 
     * @param integer $id
     * @return User
     * @throws NotFoundHttpException
     */
    private function getModel(int $id): User
    {
        $model = User::findOne($id);
        if (is_null($model)) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
    
}
