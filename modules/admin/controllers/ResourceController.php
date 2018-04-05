<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\economy\resources\Resource;
use app\modules\admin\controllers\base\AdminController;
use yii\web\NotFoundHttpException;

/**
 * 
 */
class ResourceController extends AdminController
{
    

    /**
     * Lists all Resource models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'models' => Resource::findAll(),
        ]);
    }

    /**
     * Updates an existing Resource model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Resource model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Resource the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Resource::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
