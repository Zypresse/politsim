<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\FactoryProto;

/**
 * Description of Office
 *
 * @author ilya
 */
class Office extends FactoryProto
{

    public function getId()
    {
            return 4;
    }
    
    public function getLicenses()
    {
        return [];
    }   

    public function getResourcesForBuy()
    {
        return [];
    }    
    
    public function getResourcesForSell()
    {
        return [];
    }

}
