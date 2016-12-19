<?php

namespace app\models\politics\elections;

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
    
}
