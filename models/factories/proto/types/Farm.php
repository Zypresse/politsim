<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto;

/**
 * Description of Farm
 *
 * @author ilya
 */
class Farm extends FactoryProto {
    
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 8
            ])
        ];
    }

    /**
     * Эффективность работы от региона
     * @return double
     */
    public function getRegionEff($region)
    {
        return $region->getDiggingEff(9)->k;
    }
    
}
