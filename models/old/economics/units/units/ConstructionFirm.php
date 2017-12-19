<?php

namespace app\models\economics\units\units;

use Yii,
    app\models\economics\LicenseProto,
    app\models\economics\LicenseProtoType,
    app\models\population\PopPack,
    app\models\population\PopClass,
    app\models\economics\units\UnitProto;

/**
 * 
 * @property string $name
 * 
 */
final class ConstructionFirm extends UnitProto
{
    
    public function getName()
    {
        return Yii::t('app', 'Construction firm');
    }

    public function getBuildResourcesPacks()
    {
        return [
            
        ];
    }

    public function getBuildLicenses()
    {
        return [
            LicenseProto::findOne(LicenseProtoType::BUILDING_CONSTRUCTION),
        ];
    }

    public function getWorkPopsPacks()
    {
        return [
            new PopPack(5, PopClass::WORKER),
        ];
    }

}
