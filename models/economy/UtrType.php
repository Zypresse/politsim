<?php

namespace app\models\economy;

use app\models\auth\User;

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
    public static function typeToClass(int $type): string
    {
	return [
	    static::USER => User::className(),
//            static::STATE => State::className(),
//            static::REGION => Region::className(),
//            static::CITY => City::className(),
//            static::AGENCY => Agency::className(),
//            static::POST => AgencyPost::className(),
//            static::PARTY => Party::className(),
//            static::POP => Pop::className(),
//            static::COMPANY => Company::className(),
//            static::BUILDING => Building::className(),
//            static::BUILDINGTWOTILED => BuildingTwotiled::className(),
//            static::UNIT => Unit::className(),
		][$type];
    }

}
