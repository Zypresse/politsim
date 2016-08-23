<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Fish;

/**
 * Description of FishingFarm
 *
 * @author ilya
 */
class FishingFarm extends FactoryProto 
{

    public function getId()
    {
        return 37;
    }

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
        return $region->getDiggingEff(11)->k;
    }

    public function getResourcesForBuy()
    {
        return [];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Fish
        ];
    }
}
