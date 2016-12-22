<?php

namespace app\models\politics\elections;

use app\models\User;

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
        }
    }
    
}
