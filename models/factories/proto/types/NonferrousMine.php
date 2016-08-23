<?php

namespace app\models\factories\proto\types;

use app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\NFOre;

/**
 * Шахта руды цветных металлов
 *
 * @author ilya
 */
class NonferrousMine extends Mine
{
    
    public function getId()
    {
        return 26;
    }

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 3
            ])
        ];
    }

    /**
     * Эффективность работы от региона
     * @return double
     */
    public function getRegionEff($region)
    {
        return $region->getDiggingEff(4)->k;
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
            new NFOre
        ];
    }
    
}
