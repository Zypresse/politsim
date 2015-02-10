<?php 

namespace app\components;
use Yii;
use yii\web\ErrorAction;
use yii\base\Exception;

class JsonErrorAction extends ErrorAction
{
	public function run()
	{
		if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            $name = 'No error';
        } else {
	        if ($exception instanceof Exception) {
	            $name = $exception->getName();
	        } else {
	            $name = 'Undefined error';
	        }
    	}

		return $this->controller->render('/json',['result'=>'error','error'=>$name]);
	}
}