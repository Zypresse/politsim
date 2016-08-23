<?php

namespace app\models\factories\proto\types;

use app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\REOre;

/**
 * Шахта руды редкоземельных металлов
 *
 * @author ilya
 */
class RareearthMine extends Mine
{
    
    public function getId()
    {
        return 28;
    }

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 5
            ])
        ];
    }

    /**
     * Эффективность работы от региона
     * @return double
     */
    public function getRegionEff($region)
    {
        return $region->getDiggingEff(6)->k;
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
            new REOre
        ];
    }
}
