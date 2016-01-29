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
        /* @var $state \app\models\State */
        $state = app\models\State::find()->one();
        /* @var $region1 \app\models\Region */
        $region1 = \app\models\Region::findByPk(1);
        /* @var $region2 \app\models\Region */
        $region2 = \app\models\Region::findByPk(10);
        $region1->state_id = $state->id;
        $region2->state_id = $state->id;
        $region1->save();
        $region2->save();
        
        // RenameState
        $new_name = uniqid();
        
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'proto_id' => 1,
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
        
        $this->assertEquals($new_name,$state->name);
        
        // ChangeCapital
        
        if ($state->capital == $region1->id) {
            $regId = $region2->id;
        } else {
            $regId = $region1->id;
        }
        
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'proto_id' => 2,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode(['new_capital' => $regId])
        ]);
        
        $this->assertTrue($bill->accept());
        
        $state = app\models\State::findByPk($state->id);
        
        $this->assertEquals($regId,$state->capital);
        
        // RenameRegion
        $new_name = uniqid();
        
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'proto_id' => 3,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode(['region_id' => $region1->id, 'new_name' => $new_name])
        ]);
        
        $this->assertTrue($bill->accept());
        
        $region1 = \app\models\Region::findByPk($region1->id);
        
        $this->assertEquals($new_name,$region1->name);
        
        // RenameCity
        $new_name = uniqid();
        
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'proto_id' => 4,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode(['region_id' => $region2->id, 'new_city_name' => $new_name])
        ]);
        
        $this->assertTrue($bill->accept());
        
        $region2 = \app\models\Region::findByPk($region2->id);
        
        $this->assertEquals($new_name,$region2->city);
                
        // IndependenceRegion
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'proto_id' => 5,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode(['region_id' => $region2->id])
        ]);
        
        $this->assertTrue($bill->accept());
        
        $region2 = \app\models\Region::findByPk($region2->id);
        
        $this->assertEquals(0,$region2->state_id);
                
        // ChangeFlag
        $new_flag = '/'.uniqid().'.jpg';
        
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'proto_id' => 6,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode(['new_flag' => $new_flag])
        ]);
        
        $this->assertTrue($bill->accept());
        
        $state = app\models\State::findByPk($state->id);
        
        $this->assertEquals($new_flag,$state->flag);
        
        // ConstitutionUpdate
        
        $state->allow_register_parties = 0;
        $state->save();
        
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'proto_id' => 7,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode(['article_proto_id' => 1, 'article_value' => 1])
        ]);
        
        $this->assertTrue($bill->accept());
        
        $state = app\models\State::findByPk($state->id);
        
        $this->assertEquals(1,$state->allow_register_parties);
        
        // ChangeColor        
        
        $state->color = "#eeeeee";
        $this->assertTrue($state->save());
        
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'proto_id' => 8,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode(['new_color' => "#009900"])
        ]);
        
        $this->assertTrue($bill->accept());
        
        $state = app\models\State::findByPk($state->id);
        
        $this->assertEquals("#009900",$state->color);
        
        // FormLegislature
        
        $state->legislatureOrg->delete();
        $state->legislature = 0;
        $this->assertTrue($state->save());
        
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'proto_id' => 9,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode([])
        ]);
        
        $this->assertTrue($bill->accept());
        
        $state = app\models\State::findByPk($state->id);
        
        $this->assertNotNull($state->legislatureOrg);
        
        // MakeReelects
        
        $state->executiveOrg->next_elect = strtotime("12/12/2030");
        $this->assertTrue($state->executiveOrg->save());
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'proto_id' => 10,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode(['elected_variant' => $state->executive."_1"])
        ]);
        
        $this->assertTrue($bill->accept());
        $state = app\models\State::findByPk($state->id);
        $this->assertTrue($state->executiveOrg->next_elect < time()+48*60*60+100);
        
        // ChangeLicenseRule
        
        $rule = $state->getLicenseRuleByPrototype(\app\models\licenses\proto\LicenseProto::findByPk(1));
        $rule->cost = 100;
        $rule->cost_noncitizens = 200;
        $rule->is_need_confirm = 0;
        $rule->is_need_confirm_noncitizens = 0;
        $rule->is_only_goverment = 0;
        $this->assertTrue($rule->save());
        
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'proto_id' => 11,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode([
                'license_proto_id' => 1,
                'cost' => 400,
                'cost_noncitizens' => 700,
                'is_need_confirm' => 1,
                'is_need_confirm_noncitizens' => 1,
                'is_only_goverment' => 1
            ])
        ]);
        
        $this->assertTrue($bill->accept());
    
        $rule = $state->getLicenseRuleByPrototype(\app\models\licenses\proto\LicenseProto::findByPk(1));
        $this->assertEquals(400, $rule->cost);
        $this->assertEquals(700, $rule->cost_noncitizens);
        $this->assertEquals(1, $rule->is_need_confirm);
        $this->assertEquals(1, $rule->is_need_confirm_noncitizens);
        $this->assertEquals(1, $rule->is_only_goverment);
        
        // RenameOrg
        
        $state->executiveOrg->name = "Test";
        $this->assertTrue($state->executiveOrg->save());

        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'proto_id' => 12,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode(['org_id' => $state->executive, 'new_name' => 'Правительство УНР'])
        ]);
        
        $this->assertTrue($bill->accept());
        
        $state = app\models\State::findByPk($state->id);
        $this->assertEquals('Правительство УНР', $state->executiveOrg->name);
        
        // CreateSattellite
        
        if ($state->capital == $region1->id) {
            $regId = $region2->id;
            $region2->state_id = $state->id;
            $this->assertTrue($region2->save());
        } else {
            $regId = $region1->id;
            $region1->state_id = $state->id;
            $this->assertTrue($region1->save());
        }
        
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'proto_id' => 13,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode([
                'core_id' => 1,
                'new_capital' => $regId,
                'new_name' => "Государство ".uniqid(),
                'new_short_name' => "АБВ"
            ])
        ]);
        
        $this->assertTrue($bill->accept());
        
        $state = app\models\State::findByPk($state->id);
        $this->assertNotNull($state->capitalRegion);
        $reg = \app\models\Region::findByPk($regId);
        $this->assertNotNull($reg->state);
        $this->assertNotEquals($reg->state->id, $state->id);
        
        // DropStateleader
        
        $user = \app\models\User::find()->one();
        $user->state_id = $state->id;
        $user->post_id = $state->executiveOrg->leader_post;
        $this->assertTrue($user->save());
        
        $state = app\models\State::findByPk($state->id);
        $this->assertNotNull($state->executiveOrg->leader->user);
        $this->assertEquals($user->id, $state->executiveOrg->leader->user->id);
        
        
        $bill = new app\models\bills\Bill([
            'creator' => 0,
            'proto_id' => 14,
            'state_id' => $state->id,
            'created' => time(),
            'vote_ended' => time(),
            'data' => json_encode([])
        ]);
        
        $this->assertTrue($bill->accept());
        
        $state = app\models\State::findByPk($state->id);
        $this->assertNull($state->executiveOrg->leader->user);
        
        
    }
    
}
