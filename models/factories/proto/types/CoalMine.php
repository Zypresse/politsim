<?php

namespace app\models\factories\proto\types;

use app\models\licenses\proto\LicenseProto;

/**
 * Description of CoalMine
 *
 * @author ilya
 */
class CoalMine extends Mine {
    
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 2
            ])
        ];
    }
    
}
