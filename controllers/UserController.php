<?php

namespace app\controllers;

use Yii;
use app\controllers\base\AppController;
use app\models\auth\User;

/**
 * Description of UserController
 *
 * @author ilya
 */
class UserController extends AppController
{
    
    public function actionProfile(int $id)
    {
        return $this->render('profile', [
            'model' => $this->getModel($id),
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
