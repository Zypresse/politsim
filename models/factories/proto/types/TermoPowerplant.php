<?php

namespace app\models\factories\proto\types;

use app\models\licenses\proto\LicenseProto;

/**
 * Description of Mine
 *
 * @author ilya
 */
abstract class TermoPowerplant extends Powerplant {
    
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 10
            ])
        ];
    }
    
}
