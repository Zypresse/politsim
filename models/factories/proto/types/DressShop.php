<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\types\Shop,
    app\models\resources\proto\types\Dress;

/**
 * Description of DressShop
 *
 * @author i.gorohov
 */
class DressShop extends Shop {
    
	public function getId()
	{
		return 40;
	}

    public function getResourcesForBuy()
    {
        return [
            new Dress
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Dress
        ];
    }

}
