<?php

class UserTest extends PHPUnit_Framework_TestCase 
{
    
    public static function setUpBeforeClass()
    {

        require(__DIR__ . '/../vendor/autoload.php');
        require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

        $config = require(__DIR__ . '/../config/console.php');

        $application = new yii\console\Application($config);
//        $application->run();
        
    }

    public function testCreating() 
    {
        
        $user = new app\models\User;
        $this->assertInstanceOf("app\models\User", $user);
        
    }
    
    public function testGetInstance()
    {

        $user = \app\models\User::findByPk(1);
        $this->assertInstanceOf("app\models\User", $user);
        $this->assertEquals(1, $user->id);
        
    }
    
    public function testSavingAndDelete()
    {
        $user = new app\models\User;
        $user->name = "Tester Tester";
        $user->money = 123;
        $user->uid_vk = 1;
        $this->assertTrue($user->save());
        $this->assertNotNull($user->id);
        $this->assertGreaterThan(1, $user->id);
        
        $user2 = \app\models\User::findByPk($user->id);
        $this->assertEquals($user2->id, $user->id);
        $this->assertEquals($user2->money, $user->money);
        
        $user->delete();
        
        $user3 = \app\models\User::findByPk($user->id);
        $this->assertNull($user3);
        
    }
}
