<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\types\Shop,
    app\models\resources\proto\types\Alcohol,
    app\models\resources\proto\types\Corn,
    app\models\resources\proto\types\Fish,
    app\models\resources\proto\types\Fruits,
    app\models\resources\proto\types\Meat,
    app\models\resources\proto\types\Food;

/**
 * Description of FoodShop
 *
 * @author i.gorohov
 */
class FoodShop extends Shop {
    
	public function getId()
	{
		return 39;
	}

    public function getResourcesForBuy()
    {
        return [
        	new Alcohol,
        	new Corn,
        	new Fish,
        	new Fruits,
        	new Meat,
        	new Food
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
        	new Alcohol,
        	new Corn,
        	new Fish,
        	new Fruits,
        	new Meat,
        	new Food
        ];
    }

}
