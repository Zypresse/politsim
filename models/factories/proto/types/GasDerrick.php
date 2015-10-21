<?php

namespace app\models\factories\proto\types;

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
