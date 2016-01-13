<?php

namespace app\models\factories\proto\types;

use app\models\licenses\proto\LicenseProto;

/**
 * Шахта руды редкоземельных металлов
 *
 * @author ilya
 */
class RareearthMine extends Mine {
    
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
    
}
