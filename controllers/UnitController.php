<?php

namespace app\controllers;

use app\controllers\base\BaseUnitController,
    app\models\economics\UtrType,
    app\models\economics\units\Unit,
    app\models\economics\units\UnitProto;

/**
 * 
 */
final class UnitController extends BaseUnitController
{
    
    public static function getModelClassName()
    {
        return Unit::className();
    }

    public static function getModelProtoClassName()
    {
        return UnitProto::className();
    }

    public static function getModelUtrType()
    {
        return UtrType::UNIT;
    }

}
