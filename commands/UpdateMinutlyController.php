<?php

namespace app\commands;

use yii\console\Controller,
    app\models\bills\Bill,
    app\models\HoldingDecision,
    app\models\factories\Factory,
    app\models\Vacansy;

/**
 * Update all, crontab minutly
 *
 * @author Илья
 */
class UpdateMinutlyController extends Controller {
    
    public function actionIndex() {
        
        $this->updateBills();
        
        $this->updateHoldingDecisions();
        
        $this->updateBuildinds();   
        
        $this->updateFactoryVacansies();     
        
    }

    /**
     * Update bills
     */
    private function updateBills()
    {
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
    }

    /**
     * update holding decisions
     */
    private function updateHoldingDecisions()
    {
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
    }

    /**
     * Окончание строительства
     */
    private function updateBuildinds()
    {
        // Окончание строительства
        $buildings = Factory::find()->where('builded <= '.time().' AND status = '.Factory::STATUS_UNBUILDED)->all();
        foreach ($buildings as $building) {
            $building->status = 1;
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
        $buildings = Factory::find()->where('status = '.Factory::STATUS_NOT_ENOUGHT_WORKERS.' OR status = '.Factory::STATUS_ACTIVE)->all();
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
        
}
