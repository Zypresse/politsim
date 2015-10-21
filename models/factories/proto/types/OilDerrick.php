<?php

namespace app\models\factories\proto\types;

/**
 * Description of OilDerrick
 *
 * @author ilya
 */
class OilDerrick extends Mine {
    
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 1
            ])
        ];
    }   
    
}
