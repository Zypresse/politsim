<?php

namespace app\controllers;

use app\controllers\base\BaseUnitController,
    app\models\economics\units\Building,
    app\models\economics\units\BuildingProto,
    app\models\economics\UtrType;

/**
 * 
 */
final class BuildingController extends BaseUnitController
{
    
    public static function getModelClassName()
    {
        return Building::className();
    }

    public static function getModelProtoClassName()
    {
        return BuildingProto::className();
    }

    public static function getModelUtrType()
    {
        return UtrType::BUILDING;
    }

}
