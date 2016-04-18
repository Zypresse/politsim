<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\types\Powerplant,
    app\models\licenses\proto\LicenseProto;

/**
 * АЭС
 *
 * @author ilya
 */
class NuclearPowerplant extends Powerplant
{
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 10
            ])
        ];
    }

}
