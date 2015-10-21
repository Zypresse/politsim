<?php

namespace app\models\factories\proto\types;

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
