<?php

namespace app\commands;

use yii\console\Controller,
    app\models\Region,
    app\models\State,
    app\models\Party,        
    app\models\Holding;

/**
 * Update hourly
 *
 */
class UpdateHourlyController extends Controller
{

    public function actionIndex()
    {
//        ob_start();
        
        $time = microtime(true);
        $this->updateRegions();
        printf("Updated regions: %f s.".PHP_EOL, microtime(true)-$time);
        
        $time = microtime(true);
        $this->updateStates();
        printf("Updated states: %f s.".PHP_EOL, microtime(true)-$time);
        
        $time = microtime(true);
        $this->updateParties();
        printf("Updated parties: %f s.".PHP_EOL, microtime(true)-$time);
        
        $time = microtime(true);
        $this->updateHoldings();
        printf("Updated holdings: %f s.".PHP_EOL, microtime(true)-$time);
        
        $time = microtime(true);
        $this->updateFactoryLicenseStatus();
        printf("Updated factory licenses status: %f s.".PHP_EOL, microtime(true)-$time);
        
        $time = microtime(true);
        $this->updatePopStudy();
        printf("Updated populations study: %f s.".PHP_EOL, microtime(true)-$time);
        
        $time = microtime(true);
        $this->updatePopWorkers();
        printf("Updated populations works: %f s.".PHP_EOL, microtime(true)-$time);
        
        $time = microtime(true);
        $this->updatePopAnalogies();
        printf("Updated populations analogies: %f s.".PHP_EOL, microtime(true)-$time);
        
//        ob_end_clean();
    }

    /**
     * Update regions
     */
    public function updateRegions()
    {
        $regions = Region::find()->all();
        foreach ($regions as $region) {
            $region->population = 0;
            foreach ($region->populationGroups as $pop) {
                $region->population += $pop->count;
            }
            $region->save();
        }
    }

    /**
     * Update states
     */
    public function updateStates()
    {
        $states = State::find()->with('regions')->all();
        foreach ($states as $state) {
            $state->population = 0;
            foreach ($state->regions as $region) {
                $state->population += $region->population;
            }
            $state->sum_star = 0;
            foreach ($state->users as $user) {
                $state->sum_star += $user->star;
            }
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
    public function updateParties()
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
    public function updateHoldings()
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
            
            // стоимость зданий как стоимость их постройки
            foreach ($holding->factories as $factory) {
                $capital += $factory->size * $factory->type->build_cost;
            }
            
            $capital += $holding->balance;
            
            $holding->capital = $capital;
            $holding->save();
        }
    }
    
    public function updatePopStudy()
    {
        $regions = Region::find()->all();
        foreach ($regions as $region) {
            $vacansiesSumByPopClass = [];
            $baseSpeeds = [];
            foreach ($region->vacansies as $vacansy) {
                if (isset($vacansiesSumByPopClass[$vacansy->pop_class_id])) {
                    $vacansiesSumByPopClass[$vacansy->pop_class_id] += $vacansy->count_all;
                } else {
                    $vacansiesSumByPopClass[$vacansy->pop_class_id] = $vacansy->count_all;
                    $baseSpeeds[$vacansy->pop_class_id] = $vacansy->popClass->base_speed;
                }
            }
            
            $unworkers = \app\models\Population::find()->where(['class'=>2,'region_id'=>$region->id])->all();
            shuffle($unworkers);
            
            foreach ($vacansiesSumByPopClass as $popClassID => $countAll) {
                $speed = 1*$countAll*$baseSpeeds[$popClassID]/24;
                if ($speed < 1) {
                    $speed = 1;
                } else {
                    $speed = round($speed);
                }
                $studied = 0;
                
                foreach ($unworkers as $unworker) {
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
                    
                    if (!($studied < $speed)) break;
                }
            }
        }
    }
    
    public function updatePopWorkers()
    {
        $regions = Region::find()->all();
        foreach ($regions as $region) {
            foreach ($region->vacansies as $vacansy) {
//                var_dump($vacansy->factory_id . ': ' . $vacansy->salary);
                if ($vacansy->salary == 0) continue;
                $setted = 0;
                foreach ($region->populationGroups as $popGroup) {
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
                $vacansy->count_need -= $setted;
                $vacansy->save();
            }
        }
    }
    
    public function updatePopAnalogies()
    {
        $popGroups = \app\models\Population::find()->all();
        $obrabotannue = [];
        
        foreach ($popGroups as $pop) {
        if (!(in_array($pop->getUniqueKey(), $obrabotannue))) {
            $query = new \yii\db\Query;
            $countAnalog = intval(@$query->addSelect(["SUM(count)"])
                  ->from([\app\models\Population::tableName()])
                  ->where([
                    'class' => $pop->class,
                    'nation' => $pop->nation,
                    'ideology' => $pop->ideology,
                    'sex' => $pop->sex,
                    'age' => $pop->age,
                    'factory_id' => $pop->factory_id,
                    'region_id' => $pop->region_id
                  ])->column()[0]);
            
            if ($countAnalog > $pop->count) {
                
                $obrabotannue[] = $pop->getUniqueKey();
                
                \app\models\Population::deleteAll([
                    'class' => $pop->class,
                    'nation' => $pop->nation,
                    'ideology' => $pop->ideology,
                    'sex' => $pop->sex,
                    'age' => $pop->age,
                    'factory_id' => $pop->factory_id,
                    'region_id' => $pop->region_id
                  ]);
                
                $pop->count = $countAnalog;
                $pop->isNewRecord = true;
                $pop->save();
            }
        }
        }
    }
    
    public function updateFactoryLicenseStatus()
    {
        $factories = \app\models\Factory::find()->where(['status'=>1])->all();
        foreach ($factories as $factory) {
            foreach ($factory->type->licenses as $tLicense) {
                if (!$factory->holding->isHaveLicense($factory->region->state_id,$tLicense->id)) {
                    $factory->status = 6;
                    $factory->save();
                    break;
                }
            }
        }
        
        $factories = \app\models\Factory::find()->where(['status'=>6])->all();
        foreach ($factories as $factory) {
            $allLicencesExist = true;
            foreach ($factory->type->licenses as $tLicense) {
                if (!$factory->holding->isHaveLicense($factory->region->state_id,$tLicense->id)) {
                    $allLicencesExist = false;
                    break;
                }
            }
            if ($allLicencesExist) {
                $factory->status = 1;
                $factory->save();
            }
        }
        
        unset($factories);
    }
    
}
