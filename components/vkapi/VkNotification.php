<?php

/*
 * Copyleft license
 * I dont care how you use it
 */

namespace app\components\vkapi;
use Yii,
    app\components\vkapi\VkApi;

/**
 * Description of VkNotification
 *
 * @author Илья
 * 
 * пример
 * 

use app\components\vkapi\VkNotification;

VkNotification::send("1,123,456", "Тест");

 * 
 */
class VkNotification {
    //put your code here
    public static function send($uids, $message) {
        $VK = new VkApi(Yii::$app->params['VK_APP_ID'],Yii::$app->params['VK_APP_KEY']);
       
        return $VK->api('secure.sendNotification', [
                'user_ids' => $uids,
                'message' => $message
        ]);
    }
}
