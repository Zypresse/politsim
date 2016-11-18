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
//        $conturs = TileCombiner::combine(models\Tile::find());
//        $conturs = models\Region::findByPk(1)->calcPolygon();
//        echo json_encode($conturs);
//        echo models\Region::findByPk(1)->polygon;
//        echo models\State::findByPk(1)->polygon;
        echo "Hello, world!".PHP_EOL; 
//        echo json_encode([TileCombiner::combine(models\Tile::find()->where(['and', ['<=', 'x', -430], ['>', 'x', -750], ['<=', 'y', -600], ['isLand' => true]]))]);
//        models\Tile::updateAll(['regionId' => 19], ['and', ['<=', 'x', -430], ['>', 'x', -750], ['<=', 'y', -600], ['isLand' => true]]);
//        echo models\Region::findByPk(33)->getPolygon(true);
//        echo models\State::findByPk(5)->getPolygon(true);
//        echo models\Tile::find()->count('id');
//        $state = models\State::find()->one();
//        
//        $state->updateParams(true, false);
//        $pop = 0;
//        foreach ($state->regions as $region) {
//            foreach ($region->tiles as $tile) {
//                $pop += $tile->population;
//            }
//        }
//        echo $pop;
        echo models\Pop::find()->count();
    }
    
    public function actionUpdateTiles()
    {
        Yii::$app->db->createCommand('DELETE FROM tiles WHERE 1')->execute();
        for ($i = 0; $i < 36; $i++) {
            $data = json_decode(file_get_contents(Yii::$app->basePath.'/data/default/tiles/part'.$i.'.json'));
            array_pop($data);
            echo "part$i loaded".PHP_EOL;
            foreach (array_chunk($data, 500) as $tiles) {
                Yii::$app->db->createCommand()->batchInsert('tiles', ['x','y','lat','lon', 'isWater', 'isLand', 'regionId', 'cityId'], $tiles)->execute();
            }
            echo "part$i inserted".PHP_EOL;
        }
    }
    
    public function actionUpdateRegionsAndCities()
    {
        Yii::$app->db->createCommand('DELETE FROM regions WHERE 1')->execute();
        $data = json_decode(file_get_contents(Yii::$app->basePath.'/data/default/regions.json'));
        array_pop($data);        
        foreach (array_chunk($data, 500) as $regions) {
            $regions = array_map(function($region) {
                for ($i = 4; $i <= 7; $i++) {
                    $region[$i] = $region[$i] ? json_encode($region[$i]) : null;
                }
                return $region;
            }, $regions);
            Yii::$app->db->createCommand()->batchInsert('regions', ['id', 'name', 'nameShort', 'population', 'nations', 'religions', 'ages', 'genders'], $regions)->execute();
        }
        
        Yii::$app->db->createCommand('DELETE FROM cities WHERE 1')->execute();
        $data = json_decode(file_get_contents(Yii::$app->basePath.'/data/default/cities.json'));
        array_pop($data);
        foreach (array_chunk($data, 500) as $cities) {
            
            $cities = array_map(function($city) {
                for ($i = 5; $i <= 8; $i++) {
                    $city[$i] = $city[$i] ? json_encode($city[$i]) : null;
                }
                return $city;
            }, $cities);
            Yii::$app->db->createCommand()->batchInsert('cities', ['id', 'name', 'nameShort', 'regionId', 'population', 'nations', 'religions', 'ages', 'genders'], $cities)->execute();
        }
        
        /* @var $region models\Region */
        foreach (models\Region::find()->all() as $region) {
            if ($region->biggestCity) {
                $region->link('city', $region->biggestCity);
            }
        }
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
        models\RegionConstitution::deleteAll();
        models\Pop::deleteAll();
        
        echo "models cleared".PHP_EOL;
        
        $state = new models\State([
            'name' => 'Республика Беларусь',
            'nameShort' => 'RB',
            'flag' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/Flag_of_Belarus.svg/640px-Flag_of_Belarus.svg.png',
            'mapColor' => 'C8313E'
        ]);
        $state->save();
        
        $constitution = models\StateConstitution::generate();
        $constitution->stateId = $state->id;
        $constitution->save();
        
        $executive = new models\Agency([
            'stateId' => $state->id,
            'name' => 'Правительство Республики Беларусь',
            'nameShort' => 'ПРБ',
        ]);
        $executive->save();
        $executiveConstitution = models\AgencyConstitution::generate();
        $executiveConstitution->agencyId = $executive->id;
        $executiveConstitution->save();
        
        $leaderPost = new models\AgencyPost([
            'stateId' => $state->id,
            'name' => 'Президент Республики Беларусь',
            'nameShort' => 'ПРБ'
        ]);
        $leaderPost->save();
        
        $leaderPostConstitution = models\AgencyPostConstitution::generate();
        $leaderPostConstitution->postId = $leaderPost->id;
        $leaderPostConstitution->assignmentRule = models\AgencyConstitution::ASSIGNMENT_RULE_ELECTIONS_PLURARITY;
        $leaderPostConstitution->electionsRules = models\AgencyPostConstitution::ELECTIONS_RULE_ALLOW_SELFREQUESTS + models\AgencyPostConstitution::ELECTIONS_RULE_SECOND_TOUR;
        $leaderPostConstitution->powers = models\AgencyConstitution::POWER_BILLS_ACCEPT + models\AgencyConstitution::POWER_BILLS_MAKE + models\AgencyConstitution::POWER_BILLS_VOTE + models\AgencyConstitution::POWER_BILLS_VETO;
        $leaderPostConstitution->save();
        $leaderPost->link('agencies', $executive);
        $executiveConstitution->leaderPostId = $leaderPost->id;
        
        $legislature = new models\Agency([
            'stateId' => $state->id,
            'name' => 'Национальное собрание Республики Беларусь',
            'nameShort' => 'НСРБ',
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
            'name' => 'Член национального собрания Республики Беларусь',
            'nameShort' => 'ЧНСРБ'
        ]);
        $legislatureBasePost->save();
        
        $legislatureConstitution->tempPostId = $legislatureBasePost->id;
        $legislatureConstitution->save();
        
        $legislatureBasePostConstiturion = models\AgencyPostConstitution::generate();
        $legislatureBasePostConstiturion->postId = $legislatureBasePost->id;
        $legislatureBasePostConstiturion->assignmentRule = $legislatureConstitution->assignmentRule;
        $legislatureBasePostConstiturion->powers = $legislatureConstitution->powers;
        $legislatureBasePostConstiturion->save();
        $legislatureBasePost->link('agencies', $legislature);
        
        $legislature->updateTempPosts();
        
        $city = models\City::find()->where(['name' => 'Минск'])->one();
        
        $state->cityId = $city->id;
        $state->save();
                
        echo "models saved".PHP_EOL;
        
        $regions = models\Region::find()->where(['IN', 'nameShort', ['BY-MI', 'BY-HO', 'BY-MA', 'BY-VI', 'BY-HR', 'BY-BR']])->all();

        /* @var $region models\Region */
        foreach ($regions as $i => $region) {
            echo $region->getTiles()->count('id').PHP_EOL;
            $region->stateId = $state->id;
            $region->save();
            
            $regionpost = new models\AgencyPost([
                'stateId' => $state->id,
                'name' => 'Губернатор региона «'.$region->name.'»',
                'nameShort' => 'Г'.$region->nameShort
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
                'nameShort' => 'ОИК №'.($i+1)
            ]);
            $electoralDistrict->save();
            
            echo "region {$region->name} saved".PHP_EOL;
            
            $region->getPolygon(true);
            echo "region {$region->name} polygon saved".PHP_EOL;
            
            $electoralDistrict->getPolygon(true);            
            echo "{$electoralDistrict->name} polygon saved".PHP_EOL;
            
            foreach ($region->cities as $city) {
                $city->getPolygon(true);
                echo "{$city->name} polygon saved".PHP_EOL;                
            }
        }
        $state->refresh();
        $state->getPolygon(true);
        echo "{$state->name} polygon saved".PHP_EOL;
                
    }
    
    public function actionUpdatePopulation()
    {
        
        /* @var $state models\State */
        $state = models\State::find()->one();
        foreach ($state->regions as $region) {
            $regionTilesCount = intval($region->getTiles()->count());
            $population = $region->population;
            echo $region->name.' — '.$region->population.' / '.$regionTilesCount.PHP_EOL;
            foreach ($region->cities as $city) {
                $cityTilesCount = intval($city->getTiles()->count());
                echo '  '.$city->name.' — '.$city->population.' / '.$cityTilesCount.PHP_EOL;
                
                models\Tile::updateAll(['population' => round($city->population/$cityTilesCount)], ['cityId' => $city->id]);
                $population -= $city->population;
                $regionTilesCount -= $cityTilesCount;
            }
            models\Tile::updateAll(['population' => round($population/$regionTilesCount)], ['cityId' => null, 'regionId' => $region->id]);
        }
        $state->updateParams(true, false);
    }
        
    public function actionPopulationDestinyPolygons()
    {
        
        /* @var $tiles models\Tile[] */
        $tiles = models\Tile::find()->where(['>', 'population', 0])->all();
        
        $uniques = [];
        foreach ($tiles as $tile) {
            $pop = (int)round( intval($tile->population) / $tile->area );
            if ($pop < 100) {
                $i = 0;
            } elseif ($pop < 500) {
                $i = 1;
            } elseif ($pop < 2000) {
                $i = 2;
            } else {
                $i = 3;
            }
            if (isset($uniques[$i])) {
                $uniques[$i][] = $tile;
            } else {
                $uniques[$i] = [$tile];
            }
        }
        
        $popdestiny = [];
        foreach ($uniques as $i => $tiles) {
            $path = TileCombiner::combineList($tiles);
            $popdestiny[] = [
                'i' => $i,
                'path' => $path
            ];
            echo "path for $i saved".PHP_EOL;
        }
        
        file_put_contents(Yii::$app->basePath.'/data/polygons/popdestiny.json', json_encode($popdestiny));
    }
    
    public function actionUpdatePops()
    {
        Yii::$app->db->createCommand()->truncateTable('pops')->execute();
        /* @var $state models\State */
        $state = models\State::find()->one();
        foreach ($state->regions as $region) {
            /* @var $region models\Region */
            foreach ($region->cities as $city) {
                $nations = json_decode($city->nations, true);
                if (is_array($nations)) foreach ($city->tiles as $tile) {
                    foreach ($nations as $nationId => $percents) {
                        $pop = new models\Pop([
                            'count' => round($tile->population * $percents / 100),
                            'classId' => models\PopClass::LUMPEN,
                            'nationId' => $nationId,
                            'tileId' => $tile->id,
                            'ideologies' => null,
                            'religions' => $city->religions,
                            'genders' => $city->genders,
                            'ages' => $city->ages,
                            'contentment' => 0,
                            'agression' => 0,
                            'consciousness' => 0,
                        ]);
                        if (!$pop->save()) {
                            var_dump($pop->getErrors());
                            die();
                        }
                    }
                }
                $city->updateParams(true, false);
            }
            $tilesNotInCities = $region->getTiles()->where(['cityId' => null])->all();
            
            $nations = json_decode($region->nations, true);
            if (is_array($nations)) foreach ($tilesNotInCities as $tile) {
                foreach ($nations as $nationId => $percents) {
                    $pop = new models\Pop([
                        'count' => round($tile->population * $percents / 100),
                        'classId' => models\PopClass::LUMPEN,
                        'nationId' => $nationId,
                        'tileId' => $tile->id,
                        'ideologies' => null,
                        'religions' => $region->religions,
                        'genders' => $region->genders,
                        'ages' => $region->ages,
                        'contentment' => 0,
                        'agression' => 0,
                        'consciousness' => 0,
                    ]);
                    $pop->save();
                    if (!$pop->save()) {
                        var_dump($pop->getErrors());
                        die();
                    }
                }
            }
            $region->updateParams(true, false);
        }
        $state->updateParams(true, false);
    }
}
