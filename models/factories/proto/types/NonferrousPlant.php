<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto;

/**
 * Плавильный завод цветных металлов
 *
 * @author ilya
 */
class NonferrousPlant extends FactoryProto
{
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 15
            ])
        ];
    }

}
