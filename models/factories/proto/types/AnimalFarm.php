<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto;

/**
 * ну вы понели, отсылка к Оруэллу, илитность, все дела
 *
 * @author ilya
 */
class AnimalFarm extends FactoryProto {
    
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
        return $region->getDiggingEff(12)->k;
    }
    
}
