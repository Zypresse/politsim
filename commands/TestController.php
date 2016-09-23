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
        
        
        $region1 = new models\Region([
            'name' => 'Западная Антарктида',
            'nameShort' => 'ЗА',
            'stateId' => $state->id,
        ]);
        $region1->save();
        
        $region1post = new models\AgencyPost([
            'stateId' => $state->id,
            'name' => 'Губернатор Западной Антарктиды',
            'nameShort' => 'ГБА'
        ]);
        $region1post->save();
        
        $region1postConstitution = models\AgencyPostConstitution::generate();
        $region1postConstitution->assignmentRule = models\AgencyConstitution::ASSIGNMENT_RULE_BY_STATE_LEADER;
        $region1postConstitution->postId = $region1post->id;
        $region1postConstitution->save();
        
        $region1constitution = models\RegionConstitution::generate();
        $region1constitution->regionId = $region1->id;
        $region1constitution->leaderPostId = $region1post->id;
        $region1constitution->save();
        
        
        $region2 = new models\Region([
            'name' => 'Центральная Антарктида',
            'nameShort' => 'ЦА',
            'stateId' => $state->id,
        ]);
        $region2->save();
        
        $region2post = new models\AgencyPost([
            'stateId' => $state->id,
            'name' => 'Губернатор Центральной Антарктиды',
            'nameShort' => 'ГЦА'
        ]);
        $region2post->save();
        
        $region2postConstitution = models\AgencyPostConstitution::generate();
        $region2postConstitution->assignmentRule = models\AgencyConstitution::ASSIGNMENT_RULE_BY_STATE_LEADER;
        $region2postConstitution->postId = $region2post->id;
        $region2postConstitution->save();
        
        $region2constitution = models\RegionConstitution::generate();
        $region2constitution->regionId = $region2->id;
        $region2constitution->leaderPostId = $region2post->id;
        $region2constitution->save();
        
        $region3 = new models\Region([
            'name' => 'Восточная Антарктида',
            'nameShort' => 'ВА',
            'stateId' => $state->id,
        ]);
        $region3->save();
        
        $region3post = new models\AgencyPost([
            'stateId' => $state->id,
            'name' => 'Губернатор Восточной Антарктиды',
            'nameShort' => 'ГВА'
        ]);
        $region3post->save();
        
        $region3postConstitution = models\AgencyPostConstitution::generate();
        $region3postConstitution->assignmentRule = models\AgencyConstitution::ASSIGNMENT_RULE_BY_STATE_LEADER;
        $region3postConstitution->postId = $region3post->id;
        $region3postConstitution->save();
        
        $region3constitution = models\RegionConstitution::generate();
        $region3constitution->regionId = $region3->id;
        $region3constitution->leaderPostId = $region3post->id;
        $region3constitution->save();
        
        $city = new models\City([
            'name' => 'Звёздный',
            'nameShort' => 'ЗВ',
            'regionId' => $region2->id
        ]);
        $city->save();
        
        $region2->cityId = $city->id;
        $region2->save();
        $state->cityId = $city->id;
        $state->save();
        
        $electoralDistrict = new models\ElectoralDistrict([
            'stateId' => $state->id,
            'name' => 'Первый избирательный округ',
            'nameShort' => '№1'
        ]);
        $electoralDistrict->save();
        
        echo "models saved".PHP_EOL;
        
        models\Tile::updateAll([
            'regionId' => $region1->id
        ], ['and', ['isLand' => true], ['<=', 'x', -430], ['<', 'y', -100]]);
        
        models\Tile::updateAll([
            'regionId' => $region2->id
        ], ['and', ['isLand' => true],['<=', 'x', -430], ['BETWEEN', 'y', -100, 100]]);
        
        models\Tile::updateAll([
            'regionId' => $region3->id
        ], ['and', ['isLand' => true],['<=', 'x', -430], ['>', 'y', 100]]);
        
        models\Tile::updateAll([
            'cityId' => $city->id,
        ], ['x'=>-1025,'y'=>0]);
        
        models\Tile::updateAll([
            'electoralDistrictId' => $electoralDistrict->id
        ], ['and', ['isLand' => true], ['<=', 'x', -430]]);
        
        echo "tiles updated".PHP_EOL;
        
        
        $state->refresh();
        foreach ($state->regions as $region) {
            $region->getPolygon();
            echo "{$region->name} polygon saved".PHP_EOL;
        }
        $state->getPolygon();
        echo "state polygon saved".PHP_EOL;
        $electoralDistrict->getPolygon();        
        echo "electoral district polygon saved".PHP_EOL;
        
    }
}
