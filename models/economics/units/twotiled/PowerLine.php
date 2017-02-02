<?php

namespace app\models\economics\units\twotiled;

use Yii,
    app\models\economics\ResourcePack,
    app\models\economics\ResourceProto,
    app\models\economics\units\BuildingTwotiledProto;

/**
 * 
 * @property string $name
 * 
 */
final class PowerLine extends BuildingTwotiledProto
{
    
    public function getName()
    {
        return Yii::t('app', 'Power line');
    }

    public function getBuildResourcesPacks()
    {
        return [
            new ResourcePack(2, ResourceProto::BUILDING_CONSTRUCTING),
        ];
    }

}
