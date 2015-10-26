<?php

namespace app\models\factories\proto\types;

use app\models\licenses\proto\LicenseProto;

/**
 * Description of OilDerrick
 *
 * @author ilya
 */
class OilDerrick extends Mine {
    
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
        return $region->getDiggingEff(1)->k;
    }
}
