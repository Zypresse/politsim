<?php

/*
*	Надстройка над Controller 
*	@result — для хранения JSON-ответа
*	@error — для хранения названия ошибки
*	
*		Метод _r($errorName)
*	Рендерит стандартный для API JSON-объект
*	{"result":%data%}
*	Или ошибку
*	{"result":"error","error":"%errorname%"}
*
*	@viewer_id — хранит ID залогиненого юзера
*	Логин происходит в beforeAction
*	Секретный ключ из которого генерируется auth_key — в app/config/params.php
*/

namespace app\components;

use yii;
use yii\web\Controller;
use app\models\User;

class MyController extends Controller
{
    protected $result = 'undefined';
    protected $error = false;
    protected $viewer_id = 0;
    public $layout = 'api';

    protected function _r($e = false, $addFields = []) 
    {

        if ($e) $this->error = $e;
        if ($this->error) $this->result = 'error';

        if (is_array($this->error)) $this->error = print_r($this->error,true);
        
        $ar = ['result'=>$this->result,'error'=>$this->error,'addFields'=>[]];
        foreach ($addFields as $key => $value) {
            $ar['addFields'][$key] = $value;
        }
        return $this->render('/json',$ar);
    }

    protected function _rOk()
    {
        $this->result = "ok";
        return $this->_r();
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
        if (isset($action->actionMethod)) $action->actionMethod = 'actionInvalidAuthkey';
        return true;
    }

    public function actionInvalidAuthkey()
    {
        return $this->_r("Invalid auth key");
    }
    
    /**
     * Текущий юзер
     * @return \app\model\User
     */
    protected function getUser()
    {
        return User::findByPk($this->viewer_id);
    }
}