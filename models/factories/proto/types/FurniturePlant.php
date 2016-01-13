<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto;

/**
 * завод мебели
 *
 * @author ilya
 */
class FurniturePlant extends FactoryProto
{
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 29
            ])
        ];
    }

}
