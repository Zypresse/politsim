<?php

namespace app\components;

use yii\helpers\Html,
    app\models\politics\State,
    app\models\politics\Region,
    app\models\politics\City,
    app\models\User,
    app\models\politics\Party,
    app\models\politics\Agency,
    app\models\politics\AgencyPost,
    app\models\economics\Company,
    app\models\economics\units\Building,
    app\models\economics\units\BuildingTwotiled,
    app\models\economics\units\Unit;

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
            case AgencyPost::className():
                return static::postLink($object);
            case Company::className():
                return static::companyLink($object);
            case Building::className():
                return static::buildingLink($object);
            case BuildingTwotiled::className():
                return static::buildingTwotiledLink($object);
            case Unit::className():
                return static::unitLink($object);
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
    
    /**
     * 
     * @param AgencyPost $post
     * @return string
     */
    public static function postLink(AgencyPost $post)
    {
        return static::render(null, $post->name, '#!post/view&id='.$post->id);
    }
    
    
    /**
     * 
     * @param Company $company
     * @return string
     */
    public static function companyLink(Company $company)
    {
        return static::render($company->flag, $company->name, '#!company/view&id='.$company->id);
    }
    
    /**
     * 
     * @param Building $building
     * @return string
     */
    public static function buildingLink(Building $building)
    {
        return static::render(null, $building->name, '#!building?id='.$building->id);
    }
    
    /**
     * 
     * @param BuildingTwotiled $building
     * @return string
     */
    public static function buildingTwotiledLink(BuildingTwotiled $building)
    {
        return static::render(null, $building->name, '#!building-twotiled?id='.$building->id);
    }
    
    /**
     * 
     * @param Unit $building
     * @return string
     */
    public static function unitLink(Unit $unit)
    {
        return static::render(null, $unit->name, '#!unit?id='.$unit->id);
    }
    
}
