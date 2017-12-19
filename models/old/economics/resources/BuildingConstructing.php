<?php

namespace app\models\economics\resources;

use Yii,
    app\models\economics\resources\base\NoSubtypesResourceProto;

/**
 * Услуга — строительство зданий
 */
final class BuildingConstructing extends NoSubtypesResourceProto
{
    
    public function getName()
    {
        return Yii::t('app', 'Buildings constructing');
    }

    public function getIconImage()
    {
        return '/img/resources/Construction.png';
    }

}
