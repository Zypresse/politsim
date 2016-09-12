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
        $notifications = Notification::findByUser($this->user->id)->andWhere(['dateReaded' => null])->orderBy(['id' => SORT_DESC])->all();
        foreach ($notifications as $i => $notification) {
            $notifications[$i] = [
                'id' => $notification->id,
                'protoId' => $notification->protoId,
                'icon' => $notification->getIcon(),
                'textShort' => $notification->getTextShort(),
            ];
        }
        
        $this->result = [
            'fame' => $this->user->fame,
            'trust' => $this->user->trust,
            'success' => $this->user->success,
            'notificationsCount' => count($notifications),
            'notifications' => $notifications
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
        
        Notification::markAllAsRead($this->user->id);
        
        return $this->render('index', [
            'notifications' => $notifications,
            'user' => $this->user
        ]);
    }
    
}
