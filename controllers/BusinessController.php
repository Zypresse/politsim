<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    app\components\MyController,
    app\models\economics\Utr,
    app\models\User,
    app\models\economics\Company,
    app\models\economics\Resource,
    app\models\economics\ResourceProto;

/**
 * 
 */
final class BusinessController extends MyController
{
    
    public function actionIndex()
    {
        return $this->render('index', [
            'viewer' => $this->user,
        ]);
    }
    
    public function actionShares(int $utr)
    {
        $utrModel = Utr::findByPk($utr);
        if (is_null($utrModel)) {
            throw new NotFoundHttpException(Yii::t('app', 'UTR not found'));
        }
        
        $shares = Resource::find()->where([
            'masterId' => $utr,
            'protoId' => ResourceProto::SHARE,
        ])->with('company')->all();
        
        return $this->render('shares', [
            'shareholder' => $utrModel->object,
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
