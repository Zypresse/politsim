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
        
        $this->updateRegions();
                
        $this->updateStates();
        
        $this->updateParties();  
        
        $this->updateHoldings();
        
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
    
}
