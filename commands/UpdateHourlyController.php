<?php

namespace app\commands;

use yii\console\Controller,
    app\models\Region,
    app\models\State,
    app\models\Party,        
    app\models\Holding,
    app\models\Factory,
    app\models\Population;

/**
 * Update hourly
 *
 */
class UpdateHourlyController extends Controller
{

    public function actionIndex($method = false)
    {
        
        if ($method) {            
            $time = microtime(true);
            $this->$method();
            printf("{$method}: %f s.".PHP_EOL, microtime(true)-$time);
        } else {

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
            $this->updateFactoryWorkersStatus();
            printf("Updated factory workers status: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updatePopStudy();
            printf("Updated populations study: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updatePopWorkers();
            printf("Updated populations works: %f s.".PHP_EOL, microtime(true)-$time);

            $time = microtime(true);
            $this->updatePopAnalogies();
            printf("Updated populations analogies: %f s.".PHP_EOL, microtime(true)-$time);
        }
    }

    /**
     * Update regions
     */
    private function updateRegions()
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
    private function updateStates()
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
            
            // стоимость зданий как стоимость их постройки
            foreach ($holding->factories as $factory) {
                $capital += $factory->size * $factory->type->build_cost;
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
                $allreadyStudied = \Yii::$app->db->createCommand("SELECT sum(count) FROM ".Population::tableName()." WHERE class = {$popClassID} AND region_id = {$region->id}")->queryScalar();
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
                $vacansy->count_need -= $setted;
                $vacansy->save();
            }
        }
    }
    
    private function updatePopAnalogies()
    {
        $popGroups = Population::find()->all();
        $obrabotannue = [];
        
        foreach ($popGroups as $pop) {
        if (!(in_array($pop->getUniqueKey(), $obrabotannue))) {            
            $query = new \yii\db\Query;
            $countAnalog = intval(@$query->addSelect(["SUM(count)"])
                  ->from([Population::tableName()])
                  ->where(['class' => $pop->class, 'nation' => $pop->nation, 'ideology' => $pop->ideology, 'sex' => $pop->sex, 'age' => $pop->age, 'factory_id' => $pop->factory_id, 'region_id' => $pop->region_id])->column()[0]);
            
            if ($countAnalog > $pop->count) {
                $obrabotannue[] = $pop->getUniqueKey();
                
                Population::deleteAll(
                    'class = :class AND nation = :nation AND ideology = :ideology AND sex = :sex AND age = :age AND factory_id '.($pop->factory_id?'=':'IS').' :factory_id AND region_id = :region_id AND id <> :id',
                    [ ':class' => $pop->class, ':nation' => $pop->nation, ':ideology' => $pop->ideology, ':sex' => $pop->sex, ':age' => $pop->age, ':factory_id' => $pop->factory_id, ':region_id' => $pop->region_id, ':id' => $pop->id]);
                
                $pop->count = $countAnalog;
                $pop->save();
            }
        }
        }
    }
    
    private function updateFactoryLicenseStatus()
    {
        $factories = Factory::find()->where(['status'=>Factory::STATUS_ACTIVE])->all();
        foreach ($factories as $factory) {
            foreach ($factory->type->licenses as $tLicense) {
                if (!$factory->holding->isHaveLicense($factory->region->state_id,$tLicense->id)) {
                    $factory->status = Factory::STATUS_HAVE_NOT_LICENSE;
                    $factory->save();
                    break;
                }
            }
        }
        
        $factories = Factory::find()->where(['status'=>Factory::STATUS_HAVE_NOT_LICENSE])->all();
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
    }
    
    
    private function updateFactoryWorkersStatus()
    {
        $factories = Factory::find()->where(['status'=>Factory::STATUS_ACTIVE])->all();
        foreach ($factories as $building) {
            foreach ($building->type->workers as $tWorker) {
                $count = 0;
                foreach ($building->workers as $pop) {
                    if ($pop->class == $tWorker->pop_class_id) {
                        $count += $pop->count;
                    }
                }
                if ($count < $tWorker->count*$building->size/2) {
                    $building->status = Factory::STATUS_NOT_ENOUGHT_WORKERS;
                    $building->save();
                    break;
                }
            }
        }
        
        // Проверка не набрали ли фабрики нужного числа рабочих
        $factories = Factory::find()->where(['status'=>Factory::STATUS_NOT_ENOUGHT_WORKERS])->all();
        foreach ($factories as $building) {
            foreach ($building->type->workers as $tWorker) {
                $count = 0;
                foreach ($building->workers as $pop) {
                    if ($pop->class == $tWorker->pop_class_id) {
                        $count += $pop->count;
                    }
                }
                if ($count >= $tWorker->count*$building->size/2) {
                    $building->status = Factory::STATUS_ACTIVE;
                    $building->save();
                    break;
                }
            }
        }
    }
    
}
