<?php

namespace app\commands;

use yii\console\Controller,
    app\models\Region,
    app\models\State,
    app\models\Party,
    app\models\Bill,
    app\models\Holding,
    app\models\HoldingDecision,
    app\models\Factory;

/**
 * Update all, crontab minutly
 *
 * @author Илья
 */
class UpdateMinutlyController extends Controller {
    
    public function actionIndex() {
        
        /*
         * TODO: Обновление регионов, государств и особенно партий вынести в UpdateHourly или типа того, потому что выполняется в сумме уже секунды три
         */
        
        // Update regions
        $regions = Region::find()->all();
        foreach ($regions as $region) {
            $region->population = 0;
            foreach ($region->populationGroups as $pop) {
                $region->population += $pop->count;
            }
            $region->save();
        }
        unset($regions);
                
        // Update states
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
                
        // Update parties
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
        
        // Update bills
        $bills = Bill::find()->where('accepted = 0 AND vote_ended <= '.time())->all();
        foreach ($bills as $bill) {
            if ($bill->dicktator) {
                $bill->accept();
            } else {
                $za = 0;
                $protiv = 0;
                foreach ($bill->votes as $vote) {
                    if ($vote->variant === 1) {
                        $za++;
                    } elseif ($vote->variant === 2) {
                        $protiv++;
                    }
                }
                if ($za > $protiv) {
                    $bill->accept();
                } else {
                    $bill->end();
                }
            }
        }
        unset($bills);
        
        // update holdings
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
        
        // update holding decisions
        $decisions = HoldingDecision::find()->where('accepted = 0')->all();
        foreach ($decisions as $decision) {
            $za = 0; $protiv = 0;
            foreach ($decision->votes as $vote) {
                if ($vote->stock) {
                    if (intval($vote->variant) === 1) {
                        $za+=$vote->stock->getPercents();
                    } elseif (intval($vote->variant) === 2) {
                        $protiv+=$vote->stock->getPercents();
                    }
                }
            }
            if ($za > 50.0) {
                $decision->accept();
            } elseif ($protiv > 50.0 || $decision->created < time()-7*24*60*60) {
                $decision->delete();
            }
        }
        unset($decisions);
        
        // Update building status
        // Окончание строительства
        $buildings = Factory::find()->where('builded <= '.time().' AND status = -1')->all();
        foreach ($buildings as $building) {
            $building->status = 1;
            $building->save();
        }
        // Проверка количества рабочих
        $buildings = Factory::find()->where('status = 1')->all();
        foreach ($buildings as $building) {
            foreach ($building->type->workers as $tWorker) {
                $count = 0;
                foreach ($building->workers as $link) {
                    $pop = $link->population;
                    if ($pop->class == $tWorker->pop_class_id) {
                        $count += $pop->count;
                    }
                }
                if ($count < $tWorker->count) {
                    $building->status = 5;
                    $building->save();
                }
            }
        }
        unset($buildings);
        
    }
}
