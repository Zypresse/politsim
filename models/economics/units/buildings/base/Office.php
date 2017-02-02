<?php

namespace app\models\economics\units\buildings\base;

use Yii,
    app\models\economics\ResourcePack,
    app\models\economics\ResourceProto,
    app\models\economics\units\BuildingProto;

/**
 * 
 * @property string $name
 * 
 */
final class Office extends BuildingProto
{
    
    public function getName()
    {
        return Yii::t('app', 'Office');
    }

    public function getBuildResourcesPacks()
    {
        return [
            new ResourcePack(1, ResourceProto::BUILDING_CONSTRUCTING),
        ];
    }
    
    public function getBuildLicenses()
    {
        return [
            
        ];
    }

}
