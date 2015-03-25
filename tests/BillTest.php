<?php

class BillTest extends PHPUnit_Framework_TestCase 
{
    
    public static function setUpBeforeClass()
    {

        if (!defined("MY_YII_INITIALIZED")) {
            require_once(__DIR__ . '/../vendor/autoload.php');
            require_once(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

            $config = require_once(__DIR__ . '/../config/console.php');

            new yii\console\Application($config);

            define("MY_YII_INITIALIZED", true);
        }
        
    }
    
    public function testCreateAndDelete()
    {
        $bill = new app\models\Bill;
        $this->assertInstanceOf("app\models\Bill", $bill);
        
        $bill->creator = 1;
        $bill->bill_type = 1;
        $bill->state_id = 137;
        $bill->created = time();
        $bill->vote_ended = time()+24*60*60;
        
        $this->assertTrue($bill->save());        
        $this->assertGreaterThan(1, $bill->id);
        
        $bill2 = app\models\Bill::findByPk($bill->id);
        $this->assertInstanceOf("app\models\Bill", $bill2);
        $this->assertEquals($bill2->id, $bill->id);
        $this->assertEquals($bill2->state_id, $bill->state_id);
        
        $bill->delete();
        
        $bill3 = app\models\Bill::findByPk($bill->id);
        $this->assertNull($bill3);
    }
}
