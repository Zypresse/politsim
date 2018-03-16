<?php

namespace app\commands;

use yii\console\Controller,
    app\models\politics\bills\Bill,
    app\models\economics\CompanyDecision;
//    app\models\factories\Factory,
//    app\models\factories\FactoryAuction,
//    app\models\Vacansy,
//    app\models\massmedia\Massmedia;

/**
 * Update all, crontab minutly
 *
 * @author Илья
 */
class UpdateMinutlyController extends Controller {
    
    public function actionIndex($method = false, $debug = false) {
        
        if ($method) {            
            $time = microtime(true);
            $this->$method();
            if ($debug) printf("{$method}: %f s.".PHP_EOL, microtime(true)-$time);
        } else {
            $time = microtime(true);
            $this->updateBills();
            if ($debug) printf("Updated bills: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updateCompaniesDecisions();
            if ($debug) printf("Updated companies decisions: %f s.".PHP_EOL, microtime(true)-$time);
//
//            $time = microtime(true);
//            $this->updateBuildinds();   
//            if ($debug) printf("Updated buildings builded: %f s.".PHP_EOL, microtime(true)-$time);
//
//            $time = microtime(true);
//            $this->updateFactoryVacansies();     
//            if ($debug) printf("Updated factory vacansies: %f s.".PHP_EOL, microtime(true)-$time);
//
//            $time = microtime(true);
//            $this->updateFactoryAuctions();
//            if ($debug) printf("Updated factory auctions: %f s.".PHP_EOL, microtime(true)-$time);
//
//            $time = microtime(true);
//            $this->updateMassmedia();
//            if ($debug) printf("Updated massmedia: %f s.".PHP_EOL, microtime(true)-$time);
        }                
    }

    /**
     * Update bills
     */
    private function updateBills()
    {
        $bills = Bill::find()
                ->where(['<', 'dateVotingFinished', time()])
                ->andWhere(['dateFinished' => null])
                ->all();
        foreach ($bills as $bill) {
            /* @var $bill Bill */
            if ($bill->isDictatorBill) {
                $bill->accept();
            } else {
                if ($bill->votesPlus > $bill->votesMinus) {
                    $bill->accept();
                } else {
                    $bill->decline();
                }
            }
        }
    }

    /**
     * update holding decisions
     */
    private function updateCompaniesDecisions()
    {
        $decisions = CompanyDecision::find()
                ->where(['dateFinished' => null])
                ->andWhere(['<', 'dateVotingFinished', time()])
                ->all();
        foreach ($decisions as $decision) {
            /* @var $decision CompanyDecision */
            $decision->calcResult(true);
        }
        unset($decisions);
    }

    /**
     * Окончание строительства
     */
    private function updateBuildinds()
    {
        // Окончание строительства
        $buildings = Factory::find()->where('builded <= '.time().' AND status = '.Factory::STATUS_UNBUILDED)
                ->with('proto')
                ->with('proto.workers')
                ->with('workers')
                ->with('holding')
                ->all();
        foreach ($buildings as $building) {
            /* @var $building Factory */
            $building->status = 1;
            $building->calcStatus();
            $building->save();
        }        
        unset($buildings);
    }
    
    /**
     * Размещение вакансий
     */
    private function updateFactoryVacansies()
    {
        
        // Проверка количества рабочих (размещение вакансий)
        $buildings = Factory::find()->where('status = '.Factory::STATUS_NOT_ENOUGHT_WORKERS.' OR status = '.Factory::STATUS_ACTIVE)
                ->with('proto')
                ->with('proto.workers')
                ->with('workers')
                ->all();
        foreach ($buildings as $building) {
            
            //Vacansy::deleteAll("factory_id = {$building->id}");
            
            foreach ($building->proto->workers as $tWorker) {
                $count = 0;
                foreach ($building->workers as $pop) {
                    if ($pop->class == $tWorker->pop_class_id) {
                        $count += $pop->count;
                    }
                }
//                if ($count < $tWorker->count*$building->size) {
                    if ($building->status == 1 && $count < $tWorker->count*$building->size/2) {
                        $building->status = 5;
                        $building->save();
                    } else {
                        
                        $vacansy = Vacansy::find()->where([
                            'factory_id'=>$building->id,
                            'pop_class_id'=>$tWorker->pop_class_id
                        ])->one();
                        if (is_null($vacansy)) {
                            $vacansy = new Vacansy();
                            $vacansy->factory_id = $building->id;
                            $vacansy->region_id = $building->region_id;
                            $vacansy->pop_class_id = $tWorker->pop_class_id;
                        }
                        
                        $vacansy->count_need = ($tWorker->count*$building->size - $count);
                        $vacansy->count_all = $tWorker->count*$building->size;
                        
                        $salary_value = 0;
                        foreach ($building->salaries as $salary) {
                            if ($salary->pop_class_id = $tWorker->pop_class_id) {
                                $salary_value = $salary->salary;
                                break;
                            }
                        }
                        
                        $vacansy->salary = $salary_value;
                        
                        $vacansy->save();
                    }
//                }
            }
        }
    }
    
    private function updateFactoryAuctions()
    {
        $auctions = FactoryAuction::find()->where(['<=', 'date_end', time()])->andWhere(['closed' => 0])
                ->with('lastBet')
                ->all();
        
        foreach ($auctions as $auction) {
            /* @var $auction FactoryAuction */
            $auction->end();
        }
    }
    
    private function updateMassmedia()
    {
        $massmedias = Massmedia::find()->all();
        
        foreach ($massmedias as $massmedia) {
            $massmedia->calcCoverage(true);
        }
    }
    
}