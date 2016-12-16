<?php

namespace app\components;

use yii\helpers\Html,
    app\models\politics\State,
    app\models\politics\Region,
    app\models\politics\City,
    app\models\User,
    app\models\politics\Party,
    app\models\politics\Agency;

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
            case Agency::className():
                return static::agencyLink($object);
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
     * @param State $state
     * @return string
     */
    public static function statePopulationLink(State $state)
    {
        return static::render($state->flag, $state->name, '#!population/state&id='.$state->id);
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
     * @param Region $region
     * @return string
     */
    public static function regionPopulationLink(Region $region)
    {
        return static::render($region->flag, $region->name, '#!population/region&id='.$region->id);
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
     * @param City $city
     * @return string
     */
    public static function cityPopulationLink(City $city)
    {
        return static::render($city->flag, $city->name, '#!population/city&id='.$city->id);
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
    
    /**
     * 
     * @param Agency $agency
     * @return string
     */
    public static function agencyLink(Agency $agency)
    {
        return static::render(null, $agency->name, '#!state/agency&id='.$agency->id);
    }
    
}
