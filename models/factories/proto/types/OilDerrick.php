<?php

namespace app\models\factories\proto\types;

use app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\Oil;

/**
 * Description of OilDerrick
 *
 * @author ilya
 */
class OilDerrick extends Mine
{
    
    public function getId()
    {
        return 1;
    }

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

    public function getResourcesForBuy()
    {
        return [
            new Electricity
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Oil
        ];
    }
}
