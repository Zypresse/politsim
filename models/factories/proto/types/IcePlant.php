<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto;

/**
 * Завод по производству льда
 * (на самом деле нет)
 * @author ilya
 */
class IcePlant extends FactoryProto
{
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 22
            ])
        ];
    }

}
