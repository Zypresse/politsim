<?php

namespace app\models\factories\proto\types;

use app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\NaturalGas;

/**
 * Description of GasDerrick
 *
 * @author ilya
 */
class GasDerrick extends Mine {
    
    public function getId()
    {
        return 5;
    }

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 1
            ])
        ];
    }

    /**
     * Эффективность работы от региона
     * @return double
     */
    public function getRegionEff($region)
    {
        return $region->getDiggingEff(2)->k;
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
            new NaturalGas
        ];
    }
    
}
