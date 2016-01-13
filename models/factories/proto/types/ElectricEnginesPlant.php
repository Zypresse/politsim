<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto;

/**
 * завод электродвигателей
 *
 * @author ilya
 */
class ElectricEnginesPlant extends FactoryProto
{
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 21
            ])
        ];
    }

}
