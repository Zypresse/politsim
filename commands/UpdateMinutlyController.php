<?php

namespace app\commands;

use yii\console\Controller;
use app\models\Region;
use app\models\State;
use app\models\Party;
use app\models\Bill;
use app\models\Holding;
use app\models\Notification;
use app\models\HoldingDecision;

/**
 * Update all, crontab minutly
 *
 * @author Илья
 */
class UpdateMinutlyController extends Controller {
    
    public function actionIndex() {
        
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
        $states = State::find()->all();
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
            $k = 0.9;
            foreach ($party->members as $user) {
                $party->star += $user->star*$k;
                $party->heart += $user->heart*$k;
                $party->chart_pie += $user->chart_pie*$k;
            }
            $party->star = round($party->star);
            $party->heart = round($party->heart);
            $party->chart_pie = round($party->chart_pie);
            
            if ($party->getMembersCount() === 0) {
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
            
            $capital += $holding->balance;
            
            $holding->capital = $capital;
            $holding->save();
        }
        
        // update holding decisions
        $decisions = HoldingDecision::find()->all();
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
        
    }
}
