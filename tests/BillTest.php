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
    

    public function testCreateAndAccept()
    {
        $state = app\models\State::find()->one();
        
        $new_name = uniqid();
        
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'prototype_id' => 1,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode(['new_name' => $new_name, 'new_short_name' => strtoupper(substr(uniqid(),0,3))])
        ]);
                
        $this->assertTrue($bill->save());        
        $this->assertGreaterThan(0, $bill->id);
        
        $bill2 = app\models\bills\Bill::findByPk($bill->id);
        $this->assertTrue($bill2->equals($bill));
        
        $this->assertTrue($bill->accept());
        
        $state = app\models\State::findByPk($state->id);
        
        $this->assertEquals($state->name,$new_name);
        
        $region1 = \app\models\Region::findByPk(1);
        $region2 = \app\models\Region::findByPk(10);
        $region1->state_id = $state->id;
        $region2->state_id = $state->id;
        $region1->save();
        $region2->save();
        
        if ($state->capital == $region1->id) {
            $regId = $region2->id;
        } else {
            $regId = $region1->id;
        }
        
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'prototype_id' => 2,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode(['new_capital' => $regId])
        ]);
        
        $this->assertTrue($bill->accept());
        
        $state = app\models\State::findByPk($state->id);
        
        $this->assertEquals($state->capital,$regId);
        
        $new_name = uniqid();
        
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'prototype_id' => 3,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode(['region_id' => $region1->id, 'new_name' => $new_name])
        ]);
        
        $this->assertTrue($bill->accept());
        
        $region1 = \app\models\Region::findByPk($region1->id);
        
        $this->assertEquals($region1->name,$new_name);
        
    }
    
}
