<?php

namespace app\commands;

use Yii,
    yii\db\Query,
    yii\console\Controller,
    app\models\Region,
    app\models\State,
    app\models\CoreCountryState,
    app\models\Party,        
    app\models\Holding,
    app\models\factories\Factory,
    app\models\Population,
    app\models\User,
    app\models\Dealing,
    app\models\resources\Resource,
    app\models\resources\ResourceCost,
    app\models\resources\proto\ResourceProto,
    app\models\statistics\StatisticsMining,
    app\models\statistics\StatisticsCosts;

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
            $this->updateRegions();
            if ($debug) printf("Updated regions: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updateStates();
            if ($debug) printf("Updated states: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updateParties();
            if ($debug) printf("Updated parties: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updateHoldings();
            if ($debug) printf("Updated holdings: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updatePopStudy();
            if ($debug) printf("Updated populations study: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updatePopWorkers();
            if ($debug) printf("Updated populations works: %f s.".PHP_EOL, microtime(true)-$time);
            
            $time = microtime(true);
            $this->updateFactories();
            if ($debug) printf("Updated factories: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updatePowerplantAutobuy();
            if ($debug) printf("Updated powerplants autobuy: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updatePowerplantProduction();
            if ($debug) printf("Updated powerplants production: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updateFactoryAutobuy();
            if ($debug) printf("Updated factories autobuy: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updateFactoryProduction();
            if ($debug) printf("Updated factories production: %f s.".PHP_EOL, microtime(true)-$time);
            
            $time = microtime(true);
            $this->updateResourcesCostsStatistics();
            if ($debug) printf("Updated resources costs statistics: %f s.".PHP_EOL, microtime(true)-$time);
                        
            $time = microtime(true);
            $this->updatePopPaySalaries();
            if ($debug) printf("Updated population payed salaries: %f s.".PHP_EOL, microtime(true)-$time);
                        
            $time = microtime(true);
            $this->updatePopFireJob();
            if ($debug) printf("Updated population fire job: %f s.".PHP_EOL, microtime(true)-$time);
            
            $time = microtime(true);
            $this->updatePopPurchaseResources();
            if ($debug) printf("Updated population purchase resources: %f s.".PHP_EOL, microtime(true)-$time);
            
            $time = microtime(true);
            $this->updateNonstorableResources();
            if ($debug) printf("Updated nonstorable resources: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updatePopAnalogies();
            if ($debug) printf("Updated populations analogies: %f s.".PHP_EOL, microtime(true)-$time);
                        
        }
    }

    /**
     * Update regions
     */
    private function updateRegions()
    {
        $regions = Region::find()->all();
        foreach ($regions as $region) {
            
            $sumPop = (new Query())->from(Population::tableName())->where([
                'region_id' => $region->id
            ])->sum('count');
            $sumUsers = User::find()->where(['region_id'=>$region->id])->count();
            
            $region->population = intval($sumPop) + intval($sumUsers);
            $region->save();
        }
    }

    /**
     * Update states
     */
    private function updateStates()
    {
        $states = State::find()->with('regions')->with('regions.cores')->all();
        foreach ($states as $state) {
            $state->population = 0;
            $cores = [];
            foreach ($state->regions as $region) {
                $state->population += $region->population;
                foreach ($region->cores as $core) {
                    if (isset($cores[$core->id])) {
                        $cores[$core->id]['count']++;
                    } else {
                        $cores[$core->id] = [
                            'all' => intval($core->getRegions()->count()),
                            'count' => 1
                        ];
                    }
                }
            }
            foreach ($cores as $coreId => $info) {
                $ar = ['percents' => $info['count']/$info['all']];
                CoreCountryState::findOrCreate([
                    'state_id' => $state->id,
                    'core_id' => $coreId
                ], true, $ar, $ar);
            }
            
            $state->sum_star = intval((new Query())->from(User::tableName())->where([
                'state_id' => $state->id
            ])->sum('star'));
            
            if ($state->population === 0) {
                $state->delete();
            } else {
                $state->save();
            }
        }
    }

    /**
     * Update parties
     */
    private function updateParties()
    {
        $parties = Party::find()->all();

        foreach ($parties as $party) {
            $party->star = 0;
            $party->heart = 0;
            $party->chart_pie = 0;
            $k = 1;
            foreach ($party->members as $user) {
                $party->star += $user->star*$k;
                $party->heart += $user->heart*$k;
                $party->chart_pie += $user->chart_pie*$k;
                $k *= 0.9;
            }
            $party->star = round($party->star);
            $party->heart = round($party->heart);
            $party->chart_pie = round($party->chart_pie);
            
            if (count($party->members) === 0) {
                $party->delete();
            } else {
                $party->save();
            }            
        }
    }
    
    /**
     * Update holdings
     */
    private function updateHoldings()
    {
        $holdings = Holding::find()->all();
        foreach ($holdings as $holding) {

            foreach ($holding->stocks as $stock) {
                if ($stock->count < 1) {
                    $stock->delete();
                }
            }
            
            $capital = 0.0;
            
            // пока цена на акции 1 монета
            $capital += 1* $holding->getSumStocks();
            
            // стоимость зданий как стоимость их постройки + деньги на счету
            foreach ($holding->factories as $factory) {
                $capital += $factory->size * $factory->proto->build_cost + $factory->getBalance();
            }
            
            $capital += $holding->balance;
            
            $holding->capital = $capital;
            $holding->save();
        }
    }
    
    private function updatePopStudy()
    {
        $regions = Region::find()->all();
        foreach ($regions as $region) {
//            echo $region->name.": ".PHP_EOL;
            $vacansiesSumByPopClass = [];
            $baseSpeeds = [];
            foreach ($region->vacansiesWithSalary as $vacansy) {
                if (isset($vacansiesSumByPopClass[$vacansy->pop_class_id])) {
                    $vacansiesSumByPopClass[$vacansy->pop_class_id] += $vacansy->count_all;
                } else {
                    $vacansiesSumByPopClass[$vacansy->pop_class_id] = $vacansy->count_all;
                    $baseSpeeds[$vacansy->pop_class_id] = $vacansy->popClass->base_speed;
                }
            }
            
            $unworkers = Population::find()->where(['class'=>2,'region_id'=>$region->id])->all();
            shuffle($unworkers);
            
            foreach ($vacansiesSumByPopClass as $popClassID => $countAll) {
                $allreadyStudied = Yii::$app->db->createCommand("SELECT sum(count) FROM ".Population::tableName()." WHERE class = {$popClassID} AND region_id = {$region->id}")->queryScalar();
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
                $studied = 0;
                
                foreach ($unworkers as $unworker) {
                    if ($popClassID === 1 && $unworker->sex === 1 && mt_rand() > 0.4) {
                        continue;
                    }
                    if ($unworker->count <= $speed-$studied) {
                        $unworker->class = $popClassID;
                        $unworker->save();
                        $studied+=$unworker->count;
                    } else {
                        $new_unworker = $unworker->slice($speed-$studied);
                        
                        $new_unworker->class = $popClassID;
                        $new_unworker->save();
                        
                        $studied += $new_unworker->count;
                    }
                    
                    if ($studied >= $speed) break;
                }
            }
        }
    }
    
    private function updatePopWorkers()
    {
        $regions = Region::find()->all();
        foreach ($regions as $region) {
            foreach ($region->vacansiesWithSalaryAndCount as $vacansy) {
//                var_dump($vacansy->factory_id . ': ' . $vacansy->salary);
                $setted = 0;
                foreach ($region->populationGroupsWithoutFactory as $popGroup) {
                    if ($popGroup->class == $vacansy->pop_class_id && !($popGroup->factory_id)) {
                        
                        if ($popGroup->count <= $vacansy->count_need) {
                            $popGroup->factory_id = $vacansy->factory_id;                        
                            $popGroup->save();
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
            $factory->updateStatus();
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
            if ($pop->getBalance() <= 0.001) {
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
