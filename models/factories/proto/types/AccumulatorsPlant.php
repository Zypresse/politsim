<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto,
    app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\REMetal,
    app\models\resources\proto\types\Accumulator;

/**
 * завод аккумуляторов
 *
 * @author ilya
 */
class AccumulatorsPlant extends FactoryProto
{
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 26
            ])
        ];
    }

    public function getResourcesForBuy()
    {
        return [
            new Electricity,
            new REMetal
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Accumulator
        ];
    }

}
