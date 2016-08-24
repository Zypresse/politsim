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
    
    public $layout = "api";
    
    protected $result = 'undefined';
    protected $error = false;

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
    
    /**
     * 
     * @param \yii\base\Action $action
     * @return mixed
     */
    public function beforeAction($action)
    {
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(Yii::$app->homeUrl.'#!'.str_replace('?','&',mb_substr(Yii::$app->request->url,1)));
        }
        
        return parent::beforeAction($action);
    }

    private $_user = null;
    
    /**
     * Текущий юзер
     * @return \app\models\User
     */
    protected function getUser()
    {
        if (!Yii::$app->user->isGuest && is_null($this->_user)) {
            $this->_user = Yii::$app->user->identity;
        }
        return $this->_user;
    }
}