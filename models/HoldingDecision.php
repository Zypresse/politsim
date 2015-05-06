<?php

namespace app\models;

use app\components\MyModel;
use app\models\HoldingLicense;
use app\models\StateLicense;

/**
 * Решение по управлению АО. Таблица "holding_decisions".
 *
 * @property integer $id
 * @property integer $decision_type
 * @property integer $created
 * @property integer $accepted
 * @property string $data
 * @property integer $holding_id
 * 
 * @property HoldingDecisionVote[] $votes
 * @property Holding $holding
 */
class HoldingDecision extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'holding_decisions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['decision_type', 'created', 'accepted', 'data', 'holding_id'], 'required'],
            [['decision_type', 'created', 'accepted', 'holding_id'], 'integer'],
            [['data'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'decision_type' => 'Decision Type',
            'created'       => 'Created',
            'accepted'      => 'Accepted',
            'data'          => 'Data',
            'holding_id'    => 'Holding ID',
        ];
    }

    public function getVotes()
    {
        return $this->hasMany('app\models\HoldingDecisionVote', array('decision_id' => 'id'));
    }

    public function getHolding()
    {
        return $this->hasOne('app\models\Holding', array('id' => 'holding_id'));
    }

    public function afterDelete()
    {
        foreach ($this->votes as $vote) {
            $vote->delete();
        }
    }
    
    /**
     * смена названия холдинга
     */
    const DECISION_CHANGENAME = 1;
    
    /**
     * выплата дивидентов
     */
    const DECISION_PAYDIVIDENTS = 2;
    
    /**
     * Получение лицензии
     */
    const DECISION_GIVELICENSE = 3;
    
    /**
     * Строительство фабрики
     */
    const DECISION_BUILDFABRIC = 5;

    /**
     * Принять решение
     */
    public function accept()
    {
        $data = json_decode($this->data);
        switch ($this->decision_type) {
            case self::DECISION_CHANGENAME: // смена названия холдинга
                $this->holding->name = $data->new_name;
                $this->holding->save();
                break;
            case self::DECISION_PAYDIVIDENTS: // выплата дивидентов
                if ($this->holding->balance >= $data->sum) {
                    foreach ($this->holding->stocks as $stock) {
                        $sum = $data->sum * $stock->getPercents() / 100;
                        switch (get_class($stock->master)) {
                            case 'app\models\User':
                                $stock->master->money+=$sum;
                                break;
                            case 'app\models\Post':
                                $stock->master->balance+=$sum;
                                break;
                            case 'app\models\Holding':
                                $stock->master->balance+=$sum;
                                break;
                        }
                        $stock->master->save();
                    }
                    $this->holding->balance -= $data->sum;
                    $this->holding->save();
                }
                break;
            case self::DECISION_GIVELICENSE: // Получение лицензии
                if ($this->holding->state) {
                    $stateLicense = StateLicense::find()->where(['state_id' => $this->holding->state_id, 'license_id' => $data->license_id])->one();
                    $allow        = true;
                    if (!(is_null($stateLicense))) {

                        if ($stateLicense->is_only_goverment) {
                            $allow = false;
                            foreach ($this->holding->stocks as $stock) {
                                if ($stock->post_id) {
                                    $allow = true;
                                    break;
                                }
                            }
                        }
                        if ($stateLicense->cost) {
                            if ($this->holding->balance < $stateLicense->cost) {
                                $allow = false;
                            }
                        }
                    }
                    if ($allow) {
                        $hl             = new HoldingLicense();
                        $hl->holding_id = $this->holding_id;
                        $hl->state_id   = $this->holding->state_id;
                        $hl->license_id = $data->license_id;
                        $hl->save();

                        if ($stateLicense && $stateLicense->cost) {
                            $this->holding->balance -= $stateLicense->cost;
                            $this->holding->save();
                        }
                    }
                }

                break;
            case self::DECISION_BUILDFABRIC:
                $factoryType = FactoryType::findByPk($data->factory_type);
                if ($factoryType) {
                    $region = Region::findByPk($data->region_id);
                    // TODO: Здесь проверка на возможность строить в регионе
                    if ($region) {
                        $buildCost = $factoryType->build_cost * $data->size;
                        if ($this->holding->balance >= $buildCost) {
                            
                            $data->size = intval($data->size);
                            if ($data->size < 1) {
                                $data->size = 1;
                            } elseif ($data->size > 127) {
                                $data->size = 127;
                            }
                            
                            $this->holding->balance -= $buildCost;
                            $this->holding->save();
                            
                            $factory = new Factory();
                            $factory->holding_id = $this->holding_id;
                            $factory->builded = time() + 24*60*60;
                            $factory->name = strip_tags(trim($data->name));
                            $factory->region_id = $region->id;
                            $factory->type_id = $factoryType->id;
                            $factory->status = -1;
                            $factory->size = $data->size;
                            
                            if (!$factory->save()) {
                                var_dump($factory->getErrors());
                            }
                        }
                    }
                }
                break;
        }

        $this->delete();
    }

}
