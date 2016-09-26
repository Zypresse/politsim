<?php

namespace app\commands;

use Yii,
    yii\console\Controller,
    app\components\TileCombiner,
    app\models;

class TestController extends Controller
{
    public function actionIndex()
    {
//        $conturs = TileCombiner::combine(Tile::find());
//        $conturs = models\Region::findByPk(1)->calcPolygon();
//        echo json_encode($conturs);
//        echo models\Region::findByPk(1)->polygon;
//        echo models\State::findByPk(1)->polygon;
        echo "Hello, world!";        
    }
    
    public function actionActivate()
    {
        echo models\User::updateAll(['isInvited' => 1]);
    }
    
    public function actionCleanStart()
    {
        models\State::deleteAll();
        models\StateConstitution::deleteAll();
        models\StateConstitutionLicense::deleteAll();
        models\Agency::deleteAll();
        models\AgencyConstitution::deleteAll();
        models\AgencyConstitutionLicense::deleteAll();
        models\Agency::deleteAll();
        models\AgencyPost::deleteAll();
        models\AgencyPostConstitution::deleteAll();
        models\AgencyPostConstitutionLicense::deleteAll();
        models\Election::deleteAll();
        models\ElectionRequestIndividual::deleteAll();
        models\ElectionRequestParty::deleteAll();
        models\ElectionVotePop::deleteAll();
        models\ElectionVoteUser::deleteAll();
        models\ElectoralDistrict::deleteAll();
        models\Citizenship::deleteAll();
        models\Membership::deleteAll();
        models\Party::deleteAll();
        models\PartyPost::deleteAll();
        models\Notification::deleteAll();
        models\Region::deleteAll();
        models\RegionConstitution::deleteAll();
        models\Tile::updateAll([
            'regionId' => null,
            'cityId' => null
        ]);
        
        echo "models cleared".PHP_EOL;
        
        $state = new models\State([
            'name' => 'Республика Южного Креста',
            'nameShort' => 'РЮК',
            'flag' => 'https://pp.vk.me/c421218/v421218658/75f0/T77ShYukfzw.jpg',
            'mapColor' => '01008A'
        ]);
        $state->save();
        
        $constitution = models\StateConstitution::generate();
        $constitution->stateId = $state->id;
        $constitution->save();
        
        $executive = new models\Agency([
            'stateId' => $state->id,
            'name' => 'Правительство РЮК',
            'nameShort' => 'ПРЮК',
        ]);
        $executive->save();
        $executiveConstitution = models\AgencyConstitution::generate();
        $executiveConstitution->agencyId = $executive->id;
        $executiveConstitution->save();
        
        $leaderPost = new models\AgencyPost([
            'stateId' => $state->id,
            'name' => 'Президент РЮК',
            'nameShort' => 'ПРЮК'
        ]);
        $leaderPost->save();
        
        $leaderPostConstitution = models\AgencyPostConstitution::generate();
        $leaderPostConstitution->postId = $leaderPost->id;
        $leaderPostConstitution->assignmentRule = models\AgencyConstitution::ASSIGNMENT_RULE_ELECTIONS_PLURARITY;
        $leaderPostConstitution->electionsRules = models\AgencyPostConstitution::ELECTIONS_RULE_ALLOW_SELFREQUESTS + models\AgencyPostConstitution::ELECTIONS_RULE_SECOND_TOUR;
        $leaderPostConstitution->powers = models\AgencyConstitution::POWER_BILLS_MAKE + models\AgencyConstitution::POWER_BILLS_VOTE + models\AgencyConstitution::POWER_BILLS_VETO;
        $leaderPostConstitution->save();
        
        $leaderPost->link('agencies', $executive);
        $executiveConstitution->leaderPostId = $leaderPost->id;
        
        $legislature = new models\Agency([
            'stateId' => $state->id,
            'name' => 'Парламент РЮК',
            'nameShort' => 'ПРЮК',
        ]);
        $legislature->save();
        $legislatureConstitution = models\AgencyConstitution::generate();
        $legislatureConstitution->tempPostsCount = 10;
        $legislatureConstitution->powers = models\AgencyConstitution::POWER_BILLS_MAKE + models\AgencyConstitution::POWER_BILLS_VOTE;
        $legislatureConstitution->assignmentRule = models\AgencyConstitution::ASSIGNMENT_RULE_ELECTIONS_PROPORTIONAL;
        $legislatureConstitution->agencyId = $legislature->id;
        $legislatureConstitution->save();
        
        $legislatureBasePost = new models\AgencyPost([
            'stateId' => $state->id,
            'name' => 'Член Парламента РЮК',
            'nameShort' => 'ЧПРЮК'
        ]);
        $legislatureBasePost->save();
        $legislatureBasePostConstiturion = models\AgencyPostConstitution::generate();
        $legislatureBasePostConstiturion->postId = $legislatureBasePost->id;
        $legislatureBasePostConstiturion->assignmentRule = $legislatureConstitution->assignmentRule;
        $legislatureBasePostConstiturion->powers = $legislatureConstitution->powers;
        $legislatureBasePostConstiturion->save();
        $legislatureBasePost->link('agencies', $legislature);
        
        $legislature->updateTempPosts();
        
        $city = new models\City([
            'name' => 'Звёздный',
            'nameShort' => 'ЗВ',
        ]);
        $city->save();
        
        $state->cityId = $city->id;
        $state->save();
                
        echo "models saved".PHP_EOL;
        
        $regionBorders = [
            [['<=', 'x', -430], ['>', 'x', -750], ['<=', 'y', -600]],
            [['<=', 'x', -750], ['>', 'x', -900], ['<=', 'y', -600]],
            [['<=', 'x', -900], ['<=', 'y', -600]],
            [['<=', 'x', -430], ['>', 'x', -750], ['>', 'y', -600], ['<=', 'y', 0]],
            [['<=', 'x', -750], ['>', 'x', -900], ['>', 'y', -600], ['<=', 'y', 0]],
            [['<=', 'x', -900], ['>', 'y', -600], ['<=', 'y', 0]],
            [['<=', 'x', -430], ['>', 'x', -600], ['>', 'y', 0], ['<=', 'y', 600]],
            [['<=', 'x', -600], ['>', 'x', -750], ['>', 'y', 0], ['<=', 'y', 600]],
            [['<=', 'x', -750], ['>', 'x', -900], ['>', 'y', 0], ['<=', 'y', 600]],
            [['<=', 'x', -900], ['>', 'y', 0], ['<=', 'y', 600]],
            [['<=', 'x', -430], ['>', 'x', -600], ['>', 'y', 600]],
            [['<=', 'x', -600], ['>', 'x', -750], ['>', 'y', 600]],
            [['<=', 'x', -750], ['>', 'x', -900], ['>', 'y', 600]],
            [['<=', 'x', -900], ['>', 'y', 600]]            
        ];
        
        foreach ($regionBorders as $i => $borders) {
            $where = array_merge(['and', ['isLand' => true]], $borders);            
//            $query = models\Tile::find()->where($where);

            $region = new models\Region([
                'name' => 'Дистрикт №'.($i+1),
                'nameShort' => 'Д'.($i+1),
                'stateId' => $state->id,
            ]);
            if ($i == 5) {
                $region->cityId = $city->id;
            }
            $region->save();
            if ($i == 5) {
                $city->regionId = $region->id;
            }

            $regionpost = new models\AgencyPost([
                'stateId' => $state->id,
                'name' => 'Губернатор Дистрикта №'.($i+1),
                'nameShort' => 'ГД'.($i+1)
            ]);
            $regionpost->save();

            $regionpostConstitution = models\AgencyPostConstitution::generate();
            $regionpostConstitution->assignmentRule = models\AgencyConstitution::ASSIGNMENT_RULE_BY_STATE_LEADER;
            $regionpostConstitution->postId = $regionpost->id;
            $regionpostConstitution->save();

            $regionconstitution = models\RegionConstitution::generate();
            $regionconstitution->regionId = $region->id;
            $regionconstitution->leaderPostId = $regionpost->id;
            $regionconstitution->save();
            
            $electoralDistrict = new models\ElectoralDistrict([
                'stateId' => $state->id,
                'name' => 'Избирательный округ №'.($i+1),
                'nameShort' => '№'.($i+1)
            ]);
            $electoralDistrict->save();
            
            echo "region {$i} saved".PHP_EOL;


            models\Tile::updateAll([
                'regionId' => $region->id,
                'electoralDistrictId' => $electoralDistrict->id
            ], $where);
                        
            echo "region {$i} tiles updated".PHP_EOL;
            
            $electoralDistrict->getPolygon();        
            echo "electoral district {$i} polygon saved".PHP_EOL;
        }
                        
        models\Tile::updateAll([
            'cityId' => $city->id,
        ], ['x'=>-1025,'y'=>0]);
                
        echo "city tile updated".PHP_EOL;
                
        $state->refresh();
        foreach ($state->regions as $region) {
            $region->getPolygon();
            echo "{$region->name} polygon saved".PHP_EOL;
        }
        $state->getPolygon();
        echo "state polygon saved".PHP_EOL;
        
    }
}
