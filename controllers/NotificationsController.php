<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\User,
    app\models\Notification;

/**
 * Description of NotificationController
 *
 * @author ilya
 */
class NotificationsController extends MyController
{
    
    /**
     * Возвращает JSON с новыми (непрочитанными) уведомлениями и базовой инфой о юзере
     * @return array
     */
    public function actionGetUpdates()
    {
        $this->result = [
            'fame' => 0,
            'trust' => 0,
            'success' => 0,
            'notificationsCount' => 1,
            'notifications' => [
                [
                    'id' => 1,
                    'protoId' => 0,
                    'shortText' => 'adgfdgdgsdg'
                ]
            ]
        ];
        return $this->_r();
    }
    
    /**
     * Страница со списком уведомлений
     * @return mixed
     */
    public function actionIndex()
    {
        $notifications = Notification::findByUser($this->user->id)->orderBy(['id' => SORT_DESC])->limit(5)->all();
        
        return $this->render('index', [
            'notifications' => $notifications,
            'user' => $this->user
        ]);
    }
    
}
