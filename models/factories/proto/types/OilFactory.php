<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\Oil,
    app\models\resources\proto\types\Gasoline;

/**
 * Description of OilFactory
 *
 * @author ilya
 */
class OilFactory extends FactoryProto
{
    
    public function getId()
    {
        return 10;
    }

    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 14
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [
            new Electricity,
            new Oil
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Gasoline
        ];
    }

}
