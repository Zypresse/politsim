<?php

namespace app\components;

use Yii;
use yii\web\ErrorAction as YiiErrorAction;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

/**
 * Надстройка над ErrorAction
 * Рендерит любую ошибку в стандартном для API виде
 * {"result":"error","error":"%errorname%"}
 */
class ErrorAction extends YiiErrorAction
{

    /**
     * @inheritdoc
     */
    public function run()
    {
	if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
	    // action has been invoked not from error handler, but by direct route, so we display '404 Not Found'
	    $exception = new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
	}

	$code = $exception->getCode() ?: $exception->statusCode;
	$name = $exception->getName() ?: ($this->defaultName ?: Yii::t('yii', 'Error'));
	$message = $exception->getMessage() ?: Yii::t('yii', 'An internal server error occurred.');

	if ($exception instanceof NotFoundHttpException) {
	    $message = Yii::t('app', 'There is no page are you looking for');
	}

	if (Yii::$app->request->isAjax) {
	    Yii::$app->response->format = 'json';
	    $result = ['result' => 'error', 'error' => $code . ' ' . $name . ': ' . $message];
	} else {
	    $this->controller->layout = 'landing';
	    $result = $this->controller->render('error', [
		'code' => $code,
		'name' => $name,
		'message' => $message,
		'exception' => $exception,
	    ]);
	}
	return $result;
    }

}
