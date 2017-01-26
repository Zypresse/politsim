<?php

namespace app\models\economics;

use app\models\User,
    app\models\population\Pop,
    app\models\politics\State,
    app\models\politics\Region,
    app\models\politics\City,
    app\models\politics\Agency,
    app\models\politics\AgencyPost,
    app\models\politics\Party,
    app\models\economics\Company;

/**
 * 
 */
abstract class UtrType
{
    
    const USER = 1;
    const BUILDING = 2;
    const COMPANY = 3;
    const AGENCY = 4;
    const PARTY = 5;
    const POP = 6;
    const POST = 7;
    const REGION = 8;
    const STATE = 9;
    const BUILDINGTWOTILED = 10;
    const UNIT = 11;
    const CITY = 12;
    
    /**
     * 
     * @param integer $type
     * @return string
     */
    public static function typeToClass($type)
    {
        return [
            static::USER => User::className(),
            static::STATE => State::className(),
            static::REGION => Region::className(),
            static::CITY => City::className(),
            static::AGENCY => Agency::className(),
            static::POST => AgencyPost::className(),
            static::PARTY => Party::className(),
            static::POP => Pop::className(),
            static::COMPANY => Company::className(),
        ][$type];
    }
    
}
