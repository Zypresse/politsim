<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto,
    app\models\licenses\proto\LicenseProto;

/**
 * Description of OilFactory
 *
 * @author ilya
 */
class OilFactory extends FactoryProto
{
    
    public function getLicenses()
    {
        return [
            new LicenseProto([
                'id' => 14
            ])
        ];
    }

}
