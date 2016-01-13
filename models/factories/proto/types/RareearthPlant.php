<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto;

/**
 * Плавильный завод редкоземельных металлов
 *
 * @author ilya
 */
class RareearthPlant extends FactoryProto
{
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 17
            ])
        ];
    }

}
