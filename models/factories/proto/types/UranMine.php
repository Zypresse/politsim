<?php

namespace app\models\factories\proto\types;

use app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\UOre;

/**
 * Шахта урановой руды
 *
 * @author ilya
 */
class UranMine extends Mine
{

    public function getId()
    {
        return 33;
    }
    
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 6
            ])
        ];
    }

    /**
     * Эффективность работы от региона
     * @return double
     */
    public function getRegionEff($region)
    {
        return $region->getDiggingEff(7)->k;
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
            new UOre
        ];
    }
    
}
