<?php

namespace app\models\factories\proto\types;

use app\models\licenses\proto\LicenseProto;

/**
 * Шахта руды цветных металлов
 *
 * @author ilya
 */
class NonferrousMine extends Mine {
    
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
    
}
