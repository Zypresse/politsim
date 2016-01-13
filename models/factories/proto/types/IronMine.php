<?php

namespace app\models\factories\proto\types;

use app\models\licenses\proto\LicenseProto;

/**
 * Шахта железной руды
 *
 * @author ilya
 */
class IronMine extends Mine {
    
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
    
}
