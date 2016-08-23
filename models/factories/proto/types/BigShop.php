<?php

namespace app\models\factories\proto\types;

use app\models\factories\proto\types\Shop,
	app\models\resources\proto\types\Corn,
	app\models\resources\proto\types\Dress,
	app\models\resources\proto\types\Electricity,
	app\models\resources\proto\types\Fish,
	app\models\resources\proto\types\Food,
	app\models\resources\proto\types\Fruits,
	app\models\resources\proto\types\Furniture,
	app\models\resources\proto\types\Meat,
	app\models\resources\proto\types\Water,
	app\models\resources\proto\types\WhiteGood;

/**
 * Description of BigShop
 *
 * @author i.gorohov
 */
class BigShop extends Shop {
    
	public function getId()
	{
		return 42;
	}

    public function getResourcesForBuy()
    {
        return [
        	new Corn,
        	new Dress,
        	new Electricity,
        	new Fish,
        	new Food,
        	new Fruits,
        	new Furniture,
        	new Meat,
        	new Water,
        	new WhiteGood
        ];
    }    
    
    public function getResourcesForSell()
    {
        return [
        	new Corn,
        	new Dress,
        	new Electricity,
        	new Fish,
        	new Food,
        	new Fruits,
        	new Furniture,
        	new Meat,
        	new Water,
        	new WhiteGood
        ];
    }

}
