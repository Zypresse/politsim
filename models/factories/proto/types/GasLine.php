<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\LineProto,
    app\models\licenses\proto\LicenseProto;

/**
 * Description of GasLine
 *
 * @author ilya
 */
class GasLine extends LineProto {
    
    public function getName()
    {
        return "Газопровод";
    }
}
