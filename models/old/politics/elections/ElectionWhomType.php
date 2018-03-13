<?php

namespace app\models\politics\elections;

use app\models\politics\Agency,
    app\models\politics\AgencyPost;

/**
 * Кого выбираем
 */
abstract class ElectionWhomType
{
    /**
     * Конкретный пост, выбираемый отдельно
     */
    const POST = 1;
    
    /**
     * Члены агенства выбираемые вместе
     */
    const AGENCY_MEMBERS = 2;
    
    /**
     * 
     * @param integer $type
     * @return string
     */
    public static function getClassByType(int $type)
    {
        switch ($type) {
            case static::POST:
                return AgencyPost::className();
            case static::AGENCY_MEMBERS:
                return Agency::className();
        }
    }
    
}
