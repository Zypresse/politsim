<?php

namespace app\models\politics\elections;

use app\models\User,
    app\models\politics\State,
    app\models\politics\Agency,
    app\models\politics\Region;

/**
 * Кто выбирает
 */
abstract class ElectionWhoType
{
    /**
     * Население государства
     */
    const STATE = 1;
    
    /**
     * Население электорального округа
     */
    const ELECTORAL_DISTRICT = 2;
    
    /**
     * Члены агенства
     */
    const AGENCY_MEMBERS = 3;
    
    /**
     * Население региона
     */
    const REGION = 4;
    
    /**
     * 
     * @param integer $id
     * @param integer $type
     * @param User $user
     * @return boolean
     */
    public static function canVote(int $id, int $type, User &$user)
    {
        switch ($type) {
            case static::STATE:
                return $user->isHaveCitizenship($id);
            case static::ELECTORAL_DISTRICT:
                return $user->tile && $user->tile->electoralDistrictId == $id;
            case static::AGENCY_MEMBERS:
                return $user->getPosts()->with('agencies')->where(['agencies.id' => $id])->exists();
            case static::REGION:
                return $user->tile && $user->tile->regionId == $id;
        }
    }
    
    
    /**
     * 
     * @param integer $type
     * @return string
     */
    public static function getClassByType(int $type)
    {
        switch ($type) {
            case static::STATE:
                return State::className();
            case static::ELECTORAL_DISTRICT:
                return ElectoralDistrict::className();
            case static::AGENCY_MEMBERS:
                return Agency::className();
            case static::REGION:
                return Region::className();
        }
    }
    
}
