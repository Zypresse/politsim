<?php

class UserTest extends PHPUnit_Framework_TestCase 
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
    
    public function testGetInstance()
    {

        $user = \app\models\User::findByPk(1);
        $this->assertInstanceOf("app\models\User", $user);
        $this->assertEquals(1, $user->id);
        
    }
    
    public function testSavingAndDelete()
    {
        $user = $this->createUser();
        $this->assertTrue($user->save());
        $this->assertNotNull($user->id);
        $this->assertGreaterThan(1, $user->id);
        
        $user2 = \app\models\User::findByPk($user->id);
        $this->assertNotNull($user2);
        $this->assertEquals($user2->id, $user->id);
        $this->assertEquals($user2->money, $user->money);
        
        $user->delete();
        
        $user3 = \app\models\User::findByPk($user->id);
        $this->assertNull($user3);
        
    }
    
    public function testPartyRelations()
    {
        $user = $this->createUser();
        
        $user->save();
        
        $party = new \app\models\Party();
        $party->name = "Asd";
        $party->short_name = "ASD";
        $party->ideology = 1;
        $party->state_id = 137;
        $party->leader = $user->id;
        
        $this->assertTrue($party->save());
        
        $user->party_id = $party->id;
        
        $this->assertTrue($user->save());
        $this->assertNotNull($user->party);
        $this->assertEquals($party->id, $user->party->id);
        
        $this->assertEquals(1, count($party->members));
        $this->assertTrue($user->equals($party->leaderInfo));
        $this->assertTrue($user->equals($party->members[0]));
        
        $user->leaveParty();
        $user = \app\models\User::findByPk($user->id);
        $this->assertEquals(0, $user->party_id);
        $this->assertNull($user->party);
        
        $party = \app\models\Party::findByPk($party->id);
        $this->assertNull($party);
    }
    
    public function testStateRelations()
    {
        $user = $this->createUser();
        
        $user->save();
        
        $party = new \app\models\Party();
        $party->name = "Asd";
        $party->short_name = "ASD";
        $party->ideology = 1;
        $party->state_id = 195;
        $party->leader = $user->id;
        
        $this->assertTrue($party->save());
        
        $user->party_id = $party->id;
        $user->state_id = 195;
        
        $this->assertTrue($user->save());
        $this->assertNotNull($user->state);
        $this->assertEquals(195, $user->state->id);
                
        $user->leaveState();
        $user = \app\models\User::findByPk($user->id);
        $this->assertEquals(0, $user->party_id);
        $this->assertEquals(0, $user->state_id);
        $this->assertNull($user->party);
        $this->assertNull($user->state);
        
        $party = \app\models\Party::findByPk($party->id);
        $this->assertNull($party);
    }
    
    private function createUser()
    {
        $user = new app\models\User;
        $user->name = "Tester Tester";
        $user->money = 123;
        
        return $user;
    }
}
