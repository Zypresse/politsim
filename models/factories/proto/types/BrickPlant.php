<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto;

/**
 * Кирпичный завод
 *
 * @author ilya
 */
class BrickPlant extends FactoryProto
{
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 20
            ])
        ];
    }

}
