<?php

namespace app\components;

use yii;
use yii\web\Controller;

class MyController extends Controller
{
	protected $result = 'undefined';
    protected $error = false;
    protected $viewer_id = 0;
	public $layout = 'api';

    protected function _r($e = false) 
    {
        if ($e) $this->error = $e;
        if ($this->error) $this->result = 'error';
        return $this->render('/json',['result'=>$this->result,'error'=>$this->error]);
    }

	public function beforeAction($action)
    {
        if (isset($_REQUEST['viewer_id']) && isset($_REQUEST['auth_key'])) {
            $viewer_id = intval($_REQUEST['viewer_id']);
            $auth_key = $_REQUEST['auth_key'];
            if ($viewer_id > 0 && $auth_key) {
                $real_key = md5($viewer_id.yii::$app->params['AUTH_KEY_SECRET']);
                if ($auth_key === $real_key) {
                	$this->viewer_id = $viewer_id;
                    return true;
                }
            }
        } 
        //var_dump($action);
        if (isset($action->actionMethod)) $action->actionMethod = 'actionInvalidAuthkey';
        return true;
    }

    public function actionInvalidAuthkey()
    {
        return $this->_r("Invalid auth key");
    }
}