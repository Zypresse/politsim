<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto;

/**
 * Description of Bank
 *
 * @author ilya
 */
class Bank extends FactoryProto {
    
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 13
            ])
        ];
    }
    
}
