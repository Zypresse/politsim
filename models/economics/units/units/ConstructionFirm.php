<?php

namespace app\models\economics\units\units;

use Yii,
    app\models\economics\ResourcePack,
    app\models\economics\ResourceProto,
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

}
