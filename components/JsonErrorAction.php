<?php 

/*
*	Надстройка над ErrorAction
*	Рендерит любую ошибку в стандартном для API виде
*	{"result":"error","error":"%errorname%"}
*/

namespace app\components;

use Yii;
use yii\web\ErrorAction;
use yii\base\Exception;

class JsonErrorAction extends ErrorAction
{
	public function run()
	{
            if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
                $exception = new Exception('No error');
            } 
            
            Yii::$app->response->format = 'json';
            return ['result'=>'error','error'=>$exception->getMessage()];
	}
}