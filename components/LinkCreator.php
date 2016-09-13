<?php

namespace app\components;

use yii\helpers\Html,
    app\models\State,
    app\models\Region,
    app\models\City,
    app\models\User,
    app\models\Party;

/**
 * 
 */
abstract class LinkCreator
{
    
    /**
     * 
     * @param \yii\base\Object $object
     * @return string
     */
    public static function link($object)
    {
        switch ($object->classname()) {
            case State::className():
                return static::stateLink($object);
            case Region::className():
                return static::regionLink($object);
            case City::className():
                return static::cityLink($object);
            case User::className():
                return static::userLink($object);
            case Party::className():
                return static::partyLink($object);
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
        return static::render($state->flag, $state->name, '#!state&id='.$state->id);
    }
    
    /**
     * 
     * @param Region $region
     * @return string
     */
    public static function regionLink(Region $region)
    {
        return static::render($region->flag, $region->name, '#!region&id='.$region->id);
    }
    
    /**
     * 
     * @param City $city
     * @return string
     */
    public static function cityLink(City $city)
    {
        return static::render($city->flag, $city->name, '#!city&id='.$city->id);
    }
    
    /**
     * 
     * @param User $user
     * @return string
     */
    public static function userLink(User $user)
    {
        return static::render($user->avatar, $user->name, '#!profile&id='.$user->id, 16, 'top');
    }
    
    /**
     * 
     * @param Party $party
     * @return string
     */
    public static function partyLink(Party $party)
    {
        return static::render($party->flag, $party->name, '#!party&id='.$party->id, 10);
    }
    
}
