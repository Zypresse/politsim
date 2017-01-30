<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    app\components\MyController,
    app\models\User,
    app\models\economics\Company,
    app\models\economics\Resource,
    app\models\economics\ResourceProto;

/**
 * 
 */
final class BusinessController extends MyController
{
    
    public function actionIndex($userId = false)
    {
        
        if (!$userId) {
            $userId = Yii::$app->user->id;
        }
        $user = $this->loadUser($userId);
        
        $shares = Resource::find()->where([
            'masterId' => $user->getUtr(),
            'protoId' => ResourceProto::SHARE,
        ])->all();
        
        return $this->render('index', [
            'user' => $user,
            'shares' => $shares,
            'viewer' => $this->user,
        ]);
    }
    
    public function loadUser(int $id)
    {
        $user = User::findByPk($id);
        if (is_null($user)) {
            throw new NotFoundHttpException(Yii::t('app', 'User not found'));
        }
        return $user;
    }
    
}
