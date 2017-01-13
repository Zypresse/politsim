<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    app\components\MyController,
    app\models\politics\bills\Bill;

/**
 * 
 */
final class BillsController extends MyController
{
    
    public function actionView(int $id)
    {
        $bill = $this->getBill($id);
        return $this->render('view', [
            'bill' => $bill,
            'user' => $this->user,
        ]);
    }
    
    private function getBill(int $id)
    {
        $bill = Bill::findByPk($id);
        if (is_null($bill)) {
            throw new NotFoundHttpException(Yii::t('app', 'Bill not found'));
        }
        return $bill;
    }
    
}
