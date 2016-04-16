<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\types\Shop,
    app\models\resources\proto\types\Furniture;

/**
 * Description of FurnitureShop
 *
 * @author i.gorohov
 */
class FurnitureShop extends Shop {
    
	public function getId()
	{
		return 41;
	}

    public function getResourcesForBuy()
    {
        return [
            new Furniture
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
            new Furniture
        ];
    }

}
