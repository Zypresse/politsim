<?php

namespace app\models\factories\proto\types;

use app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\FOre;

/**
 * Шахта железной руды
 *
 * @author ilya
 */
class IronMine extends Mine {
    
    public function getId()
    {
        return 22;
    }

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 4
            ])
        ];
    }

    /**
     * Эффективность работы от региона
     * @return double
     */
    public function getRegionEff($region)
    {
        return $region->getDiggingEff(5)->k;
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
            new FOre
        ];
    }

}
