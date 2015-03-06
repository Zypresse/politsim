<?php

/*
 * Copyleft license
 * I dont care how you use it
 */

namespace app\components\vkapi;
use app\components\vkapi\VkApi;

/**
 * Description of VkNotification
 *
 * @author Илья
 * 
 * пример
 * 

use app\components\vkapi\VkNotification;

VkNotification::send($user->uid_vk, "Тест");

 * 
 */
class VkNotification {
    //put your code here
    public static function send($uids, $message) {
        $VK = new VkApi(4671275,'VdZhp8XPsCeyWiHSSm7i');
       
        var_dump($VK->api('secure.sendNotification', [
                'user_ids' => $uids,
                'message' => $message
        ]));
    }
}
