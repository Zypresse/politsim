<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\User;

/**
 * Description of NotificationController
 *
 * @author ilya
 */
class NotificationController extends MyController
{
    
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
                    'shortText' => 'adgfdgdgsdg'
                ]
            ]
        ];
        return $this->_r();
    }
    
}
