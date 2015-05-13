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
        $this->updatePopStudy();
        printf("Updated populations study: %f s.".PHP_EOL, microtime(true)-$time);
        
        $time = microtime(true);
        $this->updatePopWorkers();
        printf("Updated populations works: %f s.".PHP_EOL, microtime(true)-$time);
        
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
        unset($regions);
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
        unset($states);
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
        unset($parties);
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
        unset($holdings);
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
                $speed = 1*$countAll*$baseSpeeds[$popClassID];
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
                $setted = 0;
                foreach ($region->populationGroups as $popGroup) {
                    if ($popGroup->class == $vacansy->pop_class_id && is_null($popGroup->factory)) {
                        
                        $fw = new \app\models\FactoryWorker();
                        $fw->factory_id = $vacansy->factory->id;
                        
                        if ($popGroup->count <= $vacansy->count_need) {
                            $fw->pop_id = $popGroup->id;                        
                        } else {
                            $newPG = $popGroup->slice($vacansy->count_need);
                            $fw->pop_id = $newPG->id;
                        }                
                        
                        $fw->save();
                        $setted += $fw->population->count;
                    }
                    if ($setted >= $vacansy->count_need) break;
                }
            }
        }
    }
    
}
