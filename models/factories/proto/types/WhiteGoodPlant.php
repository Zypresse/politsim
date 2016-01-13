<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto;

/**
 * завод бытовой техники
 *
 * @author ilya
 */
class WhiteGoodPlant extends FactoryProto
{
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 24
            ])
        ];
    }

}
