<?php

namespace app\models\factories\proto\types;

use app\models\licenses\proto\LicenseProto;

/**
 * Description of GasDerrick
 *
 * @author ilya
 */
class GasDerrick extends Mine {
    
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 1
            ])
        ];
    }
    
}
