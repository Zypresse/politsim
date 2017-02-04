<?php

namespace app\controllers;

use app\controllers\base\BaseUnitController,
    app\models\economics\units\BuildingTwotiled,
    app\models\economics\units\BuildingTwotiledProto,
    app\models\economics\UtrType;

/**
 * 
 */
final class BuildingTwotiledController extends BaseUnitController
{
    
    public static function getModelClassName()
    {
        return BuildingTwotiled::className();
    }

    public static function getModelProtoClassName()
    {
        return BuildingTwotiledProto::className();
    }

    public static function getModelUtrType()
    {
        return UtrType::BUILDINGTWOTILED;
    }

}
