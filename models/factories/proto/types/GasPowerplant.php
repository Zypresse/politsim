<?php

namespace app\models\factories\proto\types;

use app\models\resources\proto\types\Electricity,
    app\models\resources\proto\types\NaturalGas;

/**
 * Description of GasPowerplant
 *
 * @author ilya
 */
class GasPowerplant extends TermoPowerplant {
    
   
    public function getId()
    {
        return 7;
    }

    public function getResourcesForBuy()
    {
        return [
            new NaturalGas
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Electricity
        ];
    }
    
}