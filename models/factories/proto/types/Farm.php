<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Corn;

/**
 * Description of Farm
 *
 * @author ilya
 */
class Farm extends FactoryProto {

    public function getId()
    {
        return 2;
    }

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 8
            ])
        ];
    }

    /**
     * Эффективность работы от региона
     * @return double
     */
    public function getRegionEff($region)
    {
        return $region->getDiggingEff(9)->k;
    }
    
    public function getResourcesForBuy()
    {
        return [];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Corn
        ];
    }

}
