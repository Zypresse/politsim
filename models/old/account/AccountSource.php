<?php

namespace app\models\account;

use app\models\User;

/**
 * 
 */
abstract class AccountSource
{
    
    const GOOGLE = 1;
    const FACEBOOK = 2;
    const VKONTAKTE = 3;
    const VKAPP = 4;
    
    /**
     * 
     * @param string $source
     * @return integer
     */
    public static function getType($source)
    {
        switch ($source) {
            case 'google':
                return static::GOOGLE;
            case 'facebook':
                return static::FACEBOOK;
            case 'vkontakte':
                return static::VKONTAKTE;
            case 'vkapp':
                return static::VKAPP;
        }
        return 0;
    }
    
    /**
     * 
     * @param integer $sourceType
     * @param array $attributes
     * @return array
     */
    public static function getParams($sourceType, $attributes)
    {
        switch ($sourceType) {
            case static::GOOGLE:
                return [
                    'name' => $attributes['displayName'],
                    'genderId' => User::stringGenderToSex($attributes['gender']),
                    'avatar' => $attributes['image']['url'],
                    'avatarBig' => preg_replace("/sz=50/", "/sz=400", $attributes['image']['url'])               
                ];
            case static::FACEBOOK:
                return [
                    'name' => $attributes['name'],
                    'genderId' => User::stringGenderToSex($attributes['gender']),
                    'avatar' => "http://graph.facebook.com/{$attributes['id']}/picture",
                    'avatarBig' => "http://graph.facebook.com/{$attributes['id']}/picture?width=400&height=800"
                ];
            case static::VKONTAKTE:
            case static::VKAPP:
                return [
                    'name' => $attributes['first_name'] . ' ' . $attributes['last_name'],
                    'genderId' => intval($attributes['sex']),
                    'avatar' => $attributes['photo_50'],
                    'avatarBig' => (isset($attributes['photo_400_orig'])) ? $attributes['photo_400_orig'] : $attributes['photo_big']
                ];
        }
        
        return [];
    }
    
}
