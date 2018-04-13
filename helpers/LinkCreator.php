<?php

namespace app\helpers;

use app\helpers\Html;
use app\models\government\State;
use app\models\map\Region;
use app\models\map\City;
use app\models\auth\User;
use app\models\politics\Organization;

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
            case State::class:
                return static::stateLink($object);
            case Region::class:
                return static::regionLink($object);
            case City::class:
                return static::cityLink($object);
            case User::class:
                return static::userLink($object);
            case Organization::class:
                return static::orgLink($object);
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
    
    /**
     * 
     * @param Organization $org
     * @return string
     */
    public static function orgLink(Organization $org)
    {
        return static::render($org->flag, $org->name, ['/organization/profile', 'id' => $org->id]);
    }
    
}
