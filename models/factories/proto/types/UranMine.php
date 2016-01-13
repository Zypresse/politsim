<?php

namespace app\models\factories\proto\types;

use app\models\licenses\proto\LicenseProto;

/**
 * Шахта урановой руды
 *
 * @author ilya
 */
class UranMine extends Mine {
    
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
    
}
