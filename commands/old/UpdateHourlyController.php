<?php

namespace app\commands;

use Yii,
    yii\db\Query,
    yii\console\Controller,
    app\components\TileCombiner,
    app\models\User,
    app\models\Tile,
    app\models\population\Pop,
    app\models\population\PopClass,
    app\models\politics\City,
    app\models\politics\Region,
    app\models\politics\State,
    app\models\politics\Party,
    app\models\economics\Company;

/**
 * Update hourly
 *
 */
class UpdateHourlyController extends Controller
{

    public function actionIndex($method = false, $debug = false)
    {
        
        if ($method) {            
            $time = microtime(true);
            $this->$method();
            if ($debug) printf("{$method}: %f s.".PHP_EOL, microtime(true)-$time);
        } else {
            
            $time = microtime(true);
            $this->calcPopDestinyPolygons();
            if ($debug) printf("Updated map of population destiny: %f s.".PHP_EOL, microtime(true)-$time);
            
            $time = microtime(true);
            $this->updateUsers();
            if ($debug) printf("Updated users: %f s.".PHP_EOL, microtime(true)-$time);
            
            $time = microtime(true);
            $this->updateCities();
            if ($debug) printf("Updated cities: %f s.".PHP_EOL, microtime(true)-$time);
            
            $time = microtime(true);
            $this->updateRegions();
            if ($debug) printf("Updated regions: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updateStates();
            if ($debug) printf("Updated states: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updateParties();
            if ($debug) printf("Updated parties: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updateCompanies();
            if ($debug) printf("Updated companies: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updatePopStudy();
            if ($debug) printf("Updated populations study: %f s.".PHP_EOL, microtime(true)-$time);
//
//            $time = microtime(true);
//            $this->updatePopWorkers();
//            if ($debug) printf("Updated populations works: %f s.".PHP_EOL, microtime(true)-$time);
//            
//            $time = microtime(true);
//            $this->updatePopAnalogies();
//            if ($debug) printf("Updated populations analogies: %f s.".PHP_EOL, microtime(true)-$time);
//            
//            $time = microtime(true);
//            $this->updateFactories();
//            if ($debug) printf("Updated factories: %f s.".PHP_EOL, microtime(true)-$time);
//
//            $time = microtime(true);
//            $this->updatePowerplantAutobuy();
//            if ($debug) printf("Updated powerplants autobuy: %f s.".PHP_EOL, microtime(true)-$time);
//
//            $time = microtime(true);
//            $this->updatePowerplantProduction();
//            if ($debug) printf("Updated powerplants production: %f s.".PHP_EOL, microtime(true)-$time);
//
//            $time = microtime(true);
//            $this->updateFactoryAutobuy();
//            if ($debug) printf("Updated factories autobuy: %f s.".PHP_EOL, microtime(true)-$time);
//
//            $time = microtime(true);
//            $this->updateFactoryProduction();
//            if ($debug) printf("Updated factories production: %f s.".PHP_EOL, microtime(true)-$time);
//            
//            $time = microtime(true);
//            $this->updateResourcesCostsStatistics();
//            if ($debug) printf("Updated resources costs statistics: %f s.".PHP_EOL, microtime(true)-$time);
//                        
//            $time = microtime(true);
//            $this->updatePopPaySalaries();
//            if ($debug) printf("Updated population payed salaries: %f s.".PHP_EOL, microtime(true)-$time);
//                        
//            $time = microtime(true);
//            $this->updatePopFireJob();
//            if ($debug) printf("Updated population fire job: %f s.".PHP_EOL, microtime(true)-$time);
//            
//            $time = microtime(true);
//            $this->updatePopPurchaseResources();
//            if ($debug) printf("Updated population purchase resources: %f s.".PHP_EOL, microtime(true)-$time);
//            
//            $time = microtime(true);
//            $this->updateNonstorableResources();
//            if ($debug) printf("Updated nonstorable resources: %f s.".PHP_EOL, microtime(true)-$time);
//
//            $time = microtime(true);
//            $this->updatePopAnalogies();
//            if ($debug) printf("Updated populations analogies: %f s.".PHP_EOL, microtime(true)-$time);
                        
        }
    }
    
    private function calcPopDestinyPolygons()
    {
        
        /* @var $tiles Tile[] */
        $tiles = Tile::find()->where(['>', 'population', 0])->all();
        
        $uniques = [];
        foreach ($tiles as $tile) {
            $pop = (int)round( intval($tile->population) / $tile->area );
            if ($pop < 10) {
                $i = 0;
            } elseif ($pop < 30) {
                $i = 1;
            } elseif ($pop < 50) {
                $i = 2;
            } elseif ($pop < 100) {
                $i = 3;
            } elseif ($pop < 300) {
                $i = 4;
            } elseif ($pop < 500) {
                $i = 5;
            } elseif ($pop < 1000) {
                $i = 6;
            } elseif ($pop < 2000) {
                $i = 7;
            } else {
                $i = 8;
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
    
    private function updateUsers()
    {
        $users = User::find()->where(['isInvited' => true])->all();
        foreach ($users as $user) {
            $user->updateParams();
        }
    }

    /**
     * Update cities
     */
    private function updateCities()
    {
        $cities = City::find()->where(['is not', 'regionId', null])->all();
        foreach ($cities as $city) {
            /* @var $city City */
            $city->updateParams(true, false);
        }
    }

    /**
     * Update regions
     */
    private function updateRegions()
    {
        $regions = Region::find()->where(['is not', 'stateId', null])->all();
        foreach ($regions as $region) {
            /* @var $region Region */
            $region->updateParams(true, false);
        }
    }

    /**
     * Update states
     */
    private function updateStates()
    {
        $states = State::find()->with('regions')->all();
        foreach ($states as $state) {
            /* @var $state State */
            $state->updateParams(true, false);
        }
    }

    /**
     * Update parties
     */
    private function updateParties()
    {
        $parties = Party::find()->with('members')->all();

        foreach ($parties as $party) {
            /* @var $party Party */
            $party->updateParams();
        }
    }
    
    /**
     * Update holdings
     */
    private function updateCompanies()
    {
        $companies = Company::find()->where(['dateDeleted' => null])->with(['shares', 'licensesExpired'])->all();
        foreach ($companies as $company) {
            /* @var $company Company */
            
            foreach ($company->licensesExpired as $license) {
                foreach ($license->company->shares as $share) {
                    if (!$share->master->getUserControllerId() || !User::find()->where(['id' => $share->master->getUserControllerId()])->exists()) {
                        continue;
                    }
                    Yii::$app->notificator->licenseExpired($share->master->getUserControllerId(), $license);
                }
                $license->delete();
            }
            
            $company->updateParams();
        }
    }
    
    private function updatePopStudy()
    {
        $tiles = Tile::find()->where(['>', 'population', 0])->all();
        /* @var $tile Tile */
        foreach ($tiles as $tile) {
            $vacansiesSumByPopClass = [];
            $baseSpeeds = [];
            foreach ($tile->allUnits as $building) {
                echo $building->name.PHP_EOL;
                foreach ($building->getVacancies()->all() as $vacansy) {
                    if (isset($vacansiesSumByPopClass[$vacansy->popClassId])) {
                        $vacansiesSumByPopClass[$vacansy->popClassId] += $vacansy->countAll;
                    } else {
                        $vacansiesSumByPopClass[$vacansy->popClassId] = (int)$vacansy->countAll;
                        $baseSpeeds[$vacansy->popClassId] = $vacansy->popClass->baseSpeed;
                    }
                }
            }
            
            $unworkers = $tile->lumpens;
            shuffle($unworkers);
            
            foreach ($vacansiesSumByPopClass as $popClassID => $countAll) {
                $allreadyStudied = (int)$tile->getPops()->where(['classId' => $popClassID])->sum('count');
                
//                echo $popClassID.": ".$allreadyStudied."/".$countAll.PHP_EOL;
                if ($allreadyStudied >= $countAll) {
                    continue;
                }
                
                $speed = 1*$countAll*$baseSpeeds[$popClassID]/24;
                if ($speed < 1) {
                    $speed = 1;
                } else {
                    $speed = round($speed);
                }
                $speed += mt_rand(0, ceil($speed/10)+1);
                $studied = 0;
                
                foreach ($unworkers as $unworker) {
                    if ($unworker->count <= $speed-$studied) {
                        $unworker->classId = $popClassID;
                        $unworker->save();
                        $studied+=$unworker->count;
                    } else {
                        if ($unworker->sliceToNewClass($speed-$studied, $popClassID)) {                    
                            $studied += $speed-$studied;
                        } else {
                            var_dump($unworker->getErrors());
                        }
                    }
                    
                    if ($studied >= $speed) break;
                }
            }
        }
    }
    
    private function updatePopWorkers()
    {
        $tiles = Tile::find()->where(['>', 'population', 0])->all();
        /* @var $tile Tile */
        foreach ($tiles as $tile) {
            foreach ($tile->allUnits as $building) {
                foreach ($building->vacancies as $vacancy) {
                    if ($vacancy->countFree == 0) {
                        continue;
                    }
                    $setted = 0;
                    $unworkers = $tile->getPops()->where(['classId' => $vacancy->popClassId])->all();
                    /* @var $popGroup Pop */
                    foreach ($unworkers as $popGroup) {
                        if (!($popGroup->vacancy)) {
                            if ($popGroup->count <= $vacansy->countFree) {
                                $popGroup->link('vacancy', $vacancy);
                                $setted += $popGroup->count;
                            } else {
                                $newPG = $popGroup->slice($vacansy->count_need);
                                $newPG->factory_id = $vacansy->factory_id;
                                $newPG->save();
                                $setted += $newPG->count;
                            }                
                        }
                        if ($setted >= $vacansy->count_need) {
                            break;
                        }
                    }
                }
            }
        }
    }
    
    private function updatePopAnalogies()
    {
        $popGroups = Population::find()->orderBy('id ASC')->all();
        $obrabotannue = [];
        
        foreach ($popGroups as $pop) {
        if (!(in_array($pop->getUniqueKey(), $obrabotannue))) {            
            $query = new Query;
            $countAnalog = intval(@$query->addSelect(["SUM(count)"])
                  ->from([Population::tableName()])
                  ->where(['class' => $pop->class, 'nation' => $pop->nation, 'ideology' => $pop->ideology, 'religion' => $pop->religion, 'sex' => $pop->sex, 'age' => $pop->age, 'factory_id' => $pop->factory_id, 'region_id' => $pop->region_id])->column()[0]);
            
            if ($countAnalog > $pop->count) {
                $obrabotannue[] = $pop->getUniqueKey();
                
                Population::deleteAll(
                    'class = :class AND nation = :nation AND ideology = :ideology AND religion = :religion AND sex = :sex AND age = :age AND factory_id '.($pop->factory_id?'=':'IS').' :factory_id AND region_id = :region_id AND id <> :id',
                    [ ':class' => $pop->class, ':nation' => $pop->nation, ':ideology' => $pop->ideology, ':religion' => $pop->religion, ':sex' => $pop->sex, ':age' => $pop->age, ':factory_id' => $pop->factory_id, ':region_id' => $pop->region_id, ':id' => $pop->id]);
                
                $pop->count = $countAnalog;
                $pop->save();
            }
        }
        }
    }
    
    private function updateFactories()
    {
        $factories = Factory::find()->all();
        /* @var $factories Factory[] */
        foreach ($factories as $factory) {
            $factory->calcEff();
            $factory->updateVacansies();
            $factory->calcStatus();
            $factory->save();
        }
    }
    
    
    private function updatePowerplantAutobuy()
    {
        $powerplants = Factory::findPowerplants()->andWhere(['or',['status'=>Factory::STATUS_ACTIVE],['status'=>Factory::STATUS_NOT_ENOUGHT_RESURSES]])->all();
        
        foreach ($powerplants as $powerplant) {
            $powerplant->autobuy();
        }
    }
    
    /**
     * Производство электричества
     */
    private function updatePowerplantProduction()
    {
        $powerplants = Factory::findPowerplants()->andWhere(['or',['status'=>Factory::STATUS_ACTIVE],['status'=>Factory::STATUS_NOT_ENOUGHT_RESURSES]])->all();
        
        foreach ($powerplants as $powerplant) {
            $powerplant->work();
        }
    }
    
    private function updateFactoryAutoBuy()
    {
        $factories = Factory::findNoPowerplants()->andWhere(['or',['status'=>Factory::STATUS_ACTIVE],['status'=>Factory::STATUS_NOT_ENOUGHT_RESURSES]])->all();

        foreach ($factories as $factory) {
            $factory->autobuy();
        }
    }
    
    /**
     * Работа всех остальных предприятий
     */
    private function updateFactoryProduction()
    {
        $factories = Factory::findNoPowerplants()->andWhere(['or',['status'=>Factory::STATUS_ACTIVE],['status'=>Factory::STATUS_NOT_ENOUGHT_RESURSES]])->all();
        
        $miningResourcePrototypeIDs = [1,2];//array_map('intval', ResourceProto::find()->select('id')->where(['level' => ResourceProto::LEVEL_ZERO])->column());
        $worldStatistics = [];
        foreach ($miningResourcePrototypeIDs as $id) {
            $worldStatistics[$id] = new StatisticsMining([
                'resource_proto_id' => $id,
                'value' => 0
            ]);
        }
        
        foreach ($factories as $factory) {
            $res = $factory->work();
            if (is_array($res)) {
                foreach ($res as $resource_proto_id => $count) {
                    if (in_array($resource_proto_id, $miningResourcePrototypeIDs)) {
                        $worldStatistics[$resource_proto_id]->value += $count;
                    }
                }
            }
        }
        
        foreach ($worldStatistics as $i => $s) {
            $s->save();
        }
    }
    
    private function updateNonstorableResources()
    {
        Yii::$app->db->createCommand("UPDATE `".Resource::tableName()."` "
                . "SET count = 0 "
                . "WHERE proto_id IN ("
                    . "SELECT id FROM `".ResourceProto::tableName()."` "
                    . "WHERE level = ".ResourceProto::LEVEL_NOTSTORED
                . ")")->execute();
    }
    
    private function updateResourcesCostsStatistics()
    {        
        $resourcePrototypeIDs = [1,2];//array_map('intval', ResourceProto::find()->select('id')->column());
        $worldStatistics = [];
        foreach ($resourcePrototypeIDs as $id) {
            $worldStatistics[$id] = new StatisticsCosts([
                'resource_proto_id' => $id
            ]);
            $worldStatistics[$id]->updateValue();
            $worldStatistics[$id]->save();

            $worldStatistics[$id]->resourceProto->market_cost = $worldStatistics[$id]->value;
            $worldStatistics[$id]->resourceProto->save();
        }
    }
    
    private function updatePopPaySalaries()
    {
            
        $pops = Population::find()
                ->where(['<>','factory_id',0])
                ->with('factory')
                ->with('factory.salaries')
                ->all();
        
        $factoriesPayed = [];
        $factoriesNotPayed = [];
        
        foreach ($pops as $pop) {
            /* @var $pop Population */
            if (is_null($pop->factory)) {
                $pop->factory_id = 0;
                $pop->save();
                continue;
            }
            
            $salary = $pop->factory->getSalaryByClass($pop->class) * $pop->count;
            
            $dealing = new Dealing([
                'proto_id' => 2,
                'from_unnp' => $pop->factory->unnp,
                'to_unnp' => $pop->unnp,
                'sum' => $salary
            ]);
            if ($dealing->accept()) {
//                $pop->changeBalance($salary);
                $pop->last_salary = time();
                $pop->save();
                if (!in_array($pop->factory_id, $factoriesPayed)) {
                    $factoriesPayed[] = $pop->factory_id;
                }
            } else {
                if (!in_array($pop->factory_id, $factoriesNotPayed)) {
                    $factoriesNotPayed[] = $pop->factory_id;
                }
            }
            
        }
        
        if (count($factoriesPayed)) {
            Yii::$app->db->createCommand("UPDATE `".Factory::tableName()."` "
                    . "SET not_paying_salaries = 0 "
                    . "WHERE id IN (".implode(',', $factoriesPayed).")")
                    ->execute();
        }
        
        if (count($factoriesNotPayed)) {
            Yii::$app->db->createCommand("UPDATE `".Factory::tableName()."` "
                    . "SET not_paying_salaries = 1 "
                    . "WHERE id IN (".implode(',', $factoriesNotPayed).")")
                    ->execute();
        }
        
    }
    
    private function updatePopFireJob()
    {
        Yii::$app->db->createCommand("UPDATE `".Population::tableName()."` "
                . "SET factory_id = 0 "
                . "WHERE factory_id IN ("
                    . "SELECT id FROM `".Factory::tableName()."` "
                    . "WHERE not_paying_salaries = 1"
                . ")")->execute();
    }
    
    private function updatePopPurchaseResources()
    {
        $pops = Population::find()->with('classinfo')->with('region')->all();
        foreach ($pops as $pop) {
            /* @var $pop Population */
            
            $pop->contentment = 0;
            if ($pop->getBalance() <= 0.00001) {
                continue;
            }

            // Закупка еды
            $needFoodMax = $pop->classinfo->food_max_count*$pop->count;
            $needFoodMin = $pop->classinfo->food_min_count*$pop->count;
            $foodCosts = ResourceCost::getBuyableFood($pop->getTaxStateId(),$needFoodMax);

            $purchasedFood = $pop->autobuy($foodCosts, $needFoodMax);

            if ($purchasedFood >= $needFoodMin) {
                $pop->contentment += 0.1;
            }
            if ($purchasedFood >= $needFoodMax) {
                $pop->contentment += 0.1;
            }

            if ($pop->getBalance() <= 0.001) {
                continue;
            }
            
            // Закупка одежды
            $needDressMax = $pop->classinfo->dress_max_count*$pop->count;
            $needDressMin = $pop->classinfo->dress_min_count*$pop->count;
            $dressCosts = ResourceCost::getBuyableDress($pop->getTaxStateId(),$needDressMax);

            $purchasedDress = $pop->autobuy($dressCosts, $needDressMax);

            if ($purchasedDress >= $needDressMin) {
                $pop->contentment += 0.1;
            }
            if ($purchasedDress >= $needDressMax) {
                $pop->contentment += 0.1;
            }

            if ($pop->getBalance() <= 0.001) {
                continue;
            }
            
            // Закупка электричества
            $needElectricityMax = $pop->classinfo->energy_max*$pop->count;
            $needElectricityMin = $pop->classinfo->energy_min*$pop->count;
            $electricityCosts = ResourceCost::getBuyableElecticity($pop->getTaxStateId(),$needElectricityMax);

            $purchasedElecticity = $pop->autobuy($electricityCosts, $needElectricityMax);

            if ($purchasedElecticity >= $needElectricityMin) {
                $pop->contentment += 0.1;
            }
            if ($purchasedElecticity >= $needElectricityMax) {
                $pop->contentment += 0.1;
            }

            if ($pop->getBalance() <= 0.001) {
                continue;
            }
            
            // Закупка алкоголя
            $needAlcoholMax = $pop->classinfo->alcohol_max_count*$pop->count;
            $needAlcoholMin = $pop->classinfo->alcohol_min_count*$pop->count;
            $alcoholCosts = ResourceCost::getBuyableAlcohol($pop->getTaxStateId(),$needAlcoholMax);

            $purchasedAlcohol = $pop->autobuy($alcoholCosts, $needAlcoholMax);

            if ($purchasedAlcohol >= $needAlcoholMin) {
                $pop->contentment += 0.1;
            }
            if ($purchasedAlcohol >= $needAlcoholMax) {
                $pop->contentment += 0.1;
            }

            if ($pop->getBalance() <= 0.001) {
                continue;
            }
            
            // Закупка мебели
            $needFurnitureMax = $pop->classinfo->furniture_max_count*$pop->count;
            $needFurnitureMin = $pop->classinfo->furniture_min_count*$pop->count;
            $furnitureCosts = ResourceCost::getBuyableFurniture($pop->getTaxStateId(),$needFurnitureMax);

            $purchasedFurniture = $pop->autobuy($furnitureCosts, $needFurnitureMax);

            if ($purchasedFurniture >= $needFurnitureMin) {
                $pop->contentment += 0.1;
            }
            if ($purchasedFurniture >= $needFurnitureMax) {
                $pop->contentment += 0.1;
            }

            $pop->save();
        }
        
    }
    
}