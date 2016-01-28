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

use Yii,
    yii\web\Controller,
    app\models\User;

/**
 * 
 * @property User $user
 */

class MyController extends Controller
{
    protected $result = 'undefined';
    protected $error = false;
    protected $viewer_id = 0;

    protected function _r($e = false, $addFields = []) 
    {
        Yii::$app->response->format = 'json';
        
        if ($e) $this->error = $e;
        if ($this->error) $this->result = 'error';

        if (is_array($this->error)) $this->error = print_r($this->error,true);
        
        $ar = ['result'=>$this->result,'error'=>$this->error];
        foreach ($addFields as $key => $val) {
            $ar[$key] = $val;
        }

        return $ar;
    }

    protected function _rOk()
    {
        $this->error = false;
        $this->result = "ok";
        return $this->_r();
    }

    public function beforeAction($action)
    {
        if (isset($_REQUEST['viewer_id']) && isset($_REQUEST['auth_key'])) {
            $viewer_id = intval($_REQUEST['viewer_id']);
            $auth_key = $_REQUEST['auth_key'];
            if ($viewer_id > 0 && $auth_key) {
                $real_key = User::getRealKey($viewer_id);
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

    private $_user = null;
    
    /**
     * Текущий юзер
     * @return \app\models\User
     */
    protected function getUser()
    {
        if (is_null($this->_user)) {
            $this->_user = User::findByPk($this->viewer_id);
        }
        return $this->_user;
    }
}