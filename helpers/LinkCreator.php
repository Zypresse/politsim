<?php

namespace app\helpers;

use app\helpers\Html;
use app\models\government\State;
use app\models\map\Region;
use app\models\map\City;
use app\models\auth\User;

/**
 * 
 */
abstract class LinkCreator
{
    
    /**
     * 
     * @param \yii\base\Model $object
     * @return string
     */
    public static function link($object)
    {
        switch ($object->className()) {
            case State::className():
                return static::stateLink($object);
            case Region::className():
                return static::regionLink($object);
            case City::className():
                return static::cityLink($object);
            case User::className():
                return static::userLink($object);
            default:
                return $object->canGetProperty('name') ? $object->name : '…';
        }
    }
    
    /**
     * 
     * @param string $flag
     * @param string $name
     * @param string $link
     * @return string
     */
    private static function render($flag, $name, $link, $flagHeight = 10, $vertAlign = 'baseline')
    {
        return ($flag ? Html::img($flag, ['style' => 'height: '.$flagHeight.'px; vertical-align: '.$vertAlign.';']).' ' : '') . Html::a(Html::encode($name), $link);    
    }
    
    /**
     * 
     * @param State $state
     * @return string
     */
    public static function stateLink(State $state)
    {
        return static::render($state->flag, $state->name, ['/state', 'id' => $state->id]);
    }
    
    /**
     * 
     * @param Region $region
     * @return string
     */
    public static function regionLink(Region $region)
    {
        return static::render($region->flag, $region->name, ['/region', 'id' => $region->id]);
    }
    
    /**
     * 
     * @param City $city
     * @return string
     */
    public static function cityLink(City $city)
    {
        return static::render($city->flag, $city->name, ['/city', 'id' => $city->id]);
    }
    
    /**
     * 
     * @param User $user
     * @return string
     */
    public static function userLink(User $user)
    {
        return static::render($user->avatar, $user->name, ['/user/profile', 'id' => $user->id], 16, 'top');
    }
    
}