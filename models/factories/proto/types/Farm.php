<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto;

/**
 * Description of Farm
 *
 * @author ilya
 */
class Farm extends FactoryProto {
    
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 8
            ])
        ];
    }
    
}
