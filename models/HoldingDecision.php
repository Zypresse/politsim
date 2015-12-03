<?php

namespace app\models;

use app\components\MyModel,
    app\models\factories\Factory,
    app\models\factories\FactoryAuction,
    app\models\factories\proto\FactoryProto,
    app\models\factories\Line,
    app\models\factories\proto\LineProto,
    app\models\licenses\License,
    app\models\licenses\LicenseRule,
    app\models\licenses\proto\LicenseProto,
    app\models\State,
    app\models\Region,
    app\models\Dealing,
    app\models\User,
    app\components\MyHtmlHelper;

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
class HoldingDecision extends MyModel {

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
            'id' => 'ID',
            'decision_type' => 'Decision Type',
            'created' => 'Created',
            'accepted' => 'Accepted',
            'data' => 'Data',
            'holding_id' => 'Holding ID',
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
     * Назначение менеджера
     */
    const DECISION_SETMANAGER = 6;

    /**
     * Назначение главного офиса
     */
    const DECISION_SETMAINOFFICE = 7;

    /**
     * Переименование фабрики
     */
    const DECISION_RENAMEFABRIC = 8;

    /**
     * Продажа фабрики
     */
    const DECISION_SELLFACTORY = 9;

    /**
     * Назначение директора
     */
    const DECISION_SETDIRECTOR = 10;

    /**
     * Строительство трубопровода/ЛЭП
     */
    const DECISION_BUILDLINE = 11;
    
    /**
     * Перевод денег на счёт фабрики
     */
    const DECISION_TRANSFERMONEY = 12;
    
    public function getHtml()
    {
        $data = json_decode($this->data);
        switch ($this->decision_type) {
            case HoldingDecision::DECISION_CHANGENAME:
                return 'Переименование компании в «' . $data->new_name . '»';
            case HoldingDecision::DECISION_PAYDIVIDENTS:
                return 'Выплата дивидентов акционерам в размере ' . MyHtmlHelper::moneyFormat($data->sum);
            case HoldingDecision::DECISION_GIVELICENSE:
                $license = LicenseProto::findByPk($data->license_id);
                $state = State::findByPk($data->state_id);
                return 'Получение лицензии на вид деятельности «' . $license->name . '» в государстве ' . $state->getHtmlName();
            case HoldingDecision::DECISION_BUILDFABRIC:
                $fType = FactoryProto::findByPk($data->factory_type);
                $region = Region::findByPk($data->region_id);
                return "Строительство нового обьекта: {$fType->name}, размера {$data->size}, под названием «{$data->name}» в регионе {$region->getHtmlName()}";
            case HoldingDecision::DECISION_SETMANAGER:
                $user = User::findByPk($data->uid);
                $factory = Factory::findByPk($data->factory_id);
                return "Назначение человека по имени {$user->getHtmlName()} на должность управляющего обьектом {$factory->getHtmlName()} ({$factory->region->getHtmlName()})";
            case HoldingDecision::DECISION_SETMAINOFFICE:
                $factory = Factory::findByPk($data->factory_id);
                return "Назначение объекта {$factory->getHtmlName()} ({$factory->region->getHtmlName()}) главным офисом компании";
            case HoldingDecision::DECISION_RENAMEFABRIC:
                $factory = Factory::findByPk($data->factory_id);
                return "Переименование объекта {$factory->getHtmlName()} ({$factory->region->getHtmlName()}) в «{$data->new_name}»";
            case HoldingDecision::DECISION_SELLFACTORY:
                $factory = Factory::findByPk($data->factory_id);
                $startPrice = MyHtmlHelper::moneyFormat($data->start_price);
                $endPrice = ($data->end_price) ? " и стоп-ценой " . MyHtmlHelper::moneyFormat($data->end_price) : '';
                return "Продажа объекта {$factory->getHtmlName()} ({$factory->region->getHtmlName()}) с начальной ценой " . $startPrice . $endPrice;
            case HoldingDecision::DECISION_SETDIRECTOR:
                $user = User::findByPk($data->uid);
                return "Назначение человека по имени {$user->getHtmlName()} на должность генерального директора компании";
            case HoldingDecision::DECISION_BUILDLINE:
                $lineProto = LineProto::findByPk($data->proto_id);
                $region1 = Region::findByPk($data->region1_id);
                $region2 = Region::findByPk($data->region2_id);
                return "Строительство объекта «{$lineProto->name}» между регионами {$region1->getHtmlName()} и {$region2->getHtmlName()}";
            case HoldingDecision::DECISION_TRANSFERMONEY:
                $to = Unnp::findByPk($data->unnp)->master;
                return 'Перевод ' . MyHtmlHelper::moneyFormat($data->sum) . ' для ' . $to->getHtmlName();
        }
    }

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
                        $dealing = new Dealing([
                            'proto_id' => 3,
                            'from_unnp' => $this->holding->unnp,
                            'to_unnp' => $stock->master->unnp,
                            'sum' => ($data->sum * $stock->getPercents() / 100),
                            'time' => -1
                        ]);
                        $dealing->accept();
                    }
                }
                break;
            case self::DECISION_TRANSFERMONEY: // выплата дивидентов
                if ($this->holding->balance >= $data->sum) {
                    $dealing = new Dealing([
                        'proto_id' => 5,
                        'from_unnp' => $this->holding->unnp,
                        'to_unnp' => $data->unnp,
                        'sum' => $data->sum
                    ]);
                    $dealing->accept();
                }
                break;
            case self::DECISION_GIVELICENSE: // Получение лицензии
                if ($this->holding->state) {
                    $stateLicense = LicenseRule::find()->where([
                                'state_id' => $data->state_id,
                                'proto_id' => $data->license_id
                            ])->one();
                    $allow = true;
                    $cost = 0;
                    if (!(is_null($stateLicense))) {
                        if ($data->state_id == $this->holding->state_id) {
                            if ($stateLicense->is_only_goverment) {
                                $allow = $this->holding->isGosHolding();
                            }
                            if ($stateLicense->cost) {
                                if ($this->holding->balance < $stateLicense->cost) {
                                    $allow = false;
                                } else {
                                    $cost = $stateLicense->cost;
                                }
                            }
                        } else {
                            if ($stateLicense->is_only_goverment) {
                                $allow = false;
                            }
                            if ($stateLicense->cost_noncitizens) {
                                if ($this->holding->balance < $stateLicense->cost_noncitizens) {
                                    $allow = false;
                                } else {
                                    $cost = $stateLicense->cost_noncitizens;
                                }
                            }
                        }
                    }
                    if ($allow) {
                        $hl = new License();
                        $hl->holding_id = $this->holding_id;
                        $hl->state_id = $data->state_id;
                        $hl->proto_id = $data->license_id;
                        $hl->save();

                        if ($cost) {
                            $this->holding->balance -= $cost;
                            $this->holding->save();
                        }
                    }
                }

                break;
            case self::DECISION_BUILDFABRIC:
                $factoryType = FactoryProto::findByPk($data->factory_type);
                if ($factoryType) {
                    $region = Region::findByPk($data->region_id);
                    // TODO: Здесь проверка на возможность строить в регионе
                    if ($region) {

                        $allLicensesExitst = true;
                        foreach ($factoryType->licenses as $licenseType) {
                            $isCurrentLicenseExists = false;
                            foreach ($this->holding->licenses as $license) {
                                if ($licenseType->id == $license->proto_id && $license->state_id == $region->state_id) {
                                    $isCurrentLicenseExists = true;
                                    break;
                                }
                            }
                            if (!$isCurrentLicenseExists) {
                                $allLicensesExitst = false;
                                break;
                            }
                        }

                        if ($allLicensesExitst) {

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
                                $factory->builded = time() + 24 * 60 * 60;
                                $factory->name = strip_tags(trim($data->name));
                                $factory->region_id = $region->id;
                                $factory->proto_id = $factoryType->id;
                                $factory->status = -1;
                                $factory->size = $data->size;

                                if (!$factory->save()) {
                                    var_dump($factory->getErrors());
                                }
                            }
                        }
                    }
                }
                break;
            case self::DECISION_SETMANAGER:
                $factory = Factory::findByPk($data->factory_id);
                if ($factory->holding_id == $this->holding_id) {
                    $factory->manager_uid = $data->uid;
                    $factory->save();
                }
                break;
            case self::DECISION_SETMAINOFFICE:
                $factory = Factory::findByPk($data->factory_id);
                if ($factory->holding_id == $this->holding_id) {
                    $this->holding->main_office_id = $data->factory_id;
                    $this->holding->region_id = $factory->region_id;
//                    $this->holding->state_id = $factory->region->state_id;
                    $this->holding->save();
                }
                break;
            case self::DECISION_RENAMEFABRIC:
                $factory = Factory::findByPk($data->factory_id);
                if ($factory->holding_id == $this->holding_id) {
                    $factory->name = trim(strip_tags($data->new_name));
                    $factory->save();
                }
                break;
            case self::DECISION_SELLFACTORY:
                $factory = Factory::findByPk($data->factory_id);
                $startPrice = floatval($data->start_price) >= 0 ? floatval($data->start_price) : 0;
                $endPrice = floatval($data->end_price) > 0 ? floatval($data->end_price) : null;
                if ($factory->holding_id == $this->holding_id) {
                    $auction = new FactoryAuction([
                        'factory_id' => $factory->id,
                        'date_end' => time() + 24 * 60 * 60,
                        'start_price' => $startPrice,
                        'end_price' => $endPrice
                    ]);
                    $auction->save();
                }
                break;
            case self::DECISION_SETDIRECTOR:
                $this->holding->director_id = intval($data->uid);
                $this->holding->save();
                break;
            case self::DECISION_BUILDLINE:
                $proto = LineProto::findByPk($data->proto_id);
                if ($proto) {
                    $region1 = Region::findByPk($data->region1_id);
                    $region2 = Region::findByPk($data->region2_id);
                    // TODO: Здесь проверка на возможность строить в регионе
                    if ($region1 && $region2) {
                        
                        // TODO: тут проверка лицензий

                        $buildCost = round($proto->build_cost * $region1->calcDist($region2));
                        if ($this->holding->balance >= $buildCost) {

                            $this->holding->changeBalance(-1*$buildCost);

                            $line = new Line([
                                'holding_id' => $this->holding_id,
                                'region1_id' => $region1->id,
                                'region2_id' => $region2->id,
                                'proto_id' => $proto->id
                            ]);

                            if (!$line->save()) {
                                var_dump($line->getErrors());
                            }
                        }
                    }
                }
                break;
        }

        $this->delete();
    }

}
