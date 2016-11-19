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

class MyErrorAction extends ErrorAction
{
    public function run()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            // action has been invoked not from error handler, but by direct route, so we display '404 Not Found'
            $exception = new HttpException(404, Yii::t('yii', 'Page not found.'));
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }
        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = $this->defaultName ?: Yii::t('yii', 'Error');
        }
        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = $this->defaultMessage ?: Yii::t('yii', 'An internal server error occurred.');
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = 'json';
            return ['result'=>'error','error'=>$name.': '.$message];
        } else {
            return $this->controller->render('error', [                
                'name' => $name,
                'message' => $message,
                'exception' => $exception,
            ]);
        }
    }
    
}