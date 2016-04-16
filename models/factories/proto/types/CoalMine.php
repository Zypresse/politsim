<?php

namespace app\models\factories\proto\types;

use app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Coal,
    app\models\resources\proto\types\Electricity;

/**
 * Description of CoalMine
 *
 * @author ilya
 */
class CoalMine extends Mine {
    
    public function getId()
    {
        return 9;
    }

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 2
            ])
        ];
    }

    /**
     * Эффективность работы от региона
     * @return double
     */
    public function getRegionEff($region)
    {
        return $region->getDiggingEff(3)->k;
    }

    public function getResourcesForBuy()
    {
        return [
            new Electricity
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Coal
        ];
    }
    
}
