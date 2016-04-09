<?php

use app\models\tiles\TileFactory;

/**
 * Description of TileFactoryTest
 *
 * @author i.gorohov
 */
class TileFactoryTest extends PHPUnit_Framework_TestCase {
    
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
    
    public function testGenerate()
    {
        $tile = TileFactory::generate(300, 300);
//        var_dump($tile->attributes);
        $this->assertEquals(46.9134, $tile->e_lat, "Latitude not equal (calculated {$tile->e_lat}", 0.001);
        $this->assertTrue($tile->save());
        
    }
}
