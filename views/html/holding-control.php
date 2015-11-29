<?php
/*
 * Copyleft license
 * I dont care how you use it
 */

use app\components\MyHtmlHelper,
    yii\helpers\Html,
    app\models\HoldingDecision,
    app\models\factories\proto\FactoryProtoCategory,
    app\models\factories\proto\FactoryProto,
    app\models\factories\Factory,
    app\models\factories\proto\LineProto,
    app\models\resurses\proto\ResurseProto,
    app\models\licenses\proto\LicenseProto,
    app\models\User,
    app\models\State,
    app\models\Region;

/* @var $user \app\models\User */
$userStock = $user->getShareholderStock($holding);
$factoryCategories = FactoryProtoCategory::find()->all();
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">

<h1>Управление «<?= $holding->name ?>»</h1>
<p>Директор: <?=$holding->director?  MyHtmlHelper::a($holding->director->name,'load_page("profile",{"id":'.$holding->director_id.'})'):'<em>не назначен</em>'?></p>
<p>Капитализация: <?= number_format($holding->capital, 0, '', ' ') ?> <?= MyHtmlHelper::icon('money') ?></p>
<p>Баланс: <?= number_format($holding->balance, 0, '', ' ') ?> <?= MyHtmlHelper::icon('money') ?></p>
<? if ($holding->state) { ?>
    <p>Компания зарегистрирована в государстве: <?= Html::a($holding->state->name, '#', ['onclick' => "load_page('state-info',{'id':{$holding->state_id}})"]) ?></p>
<? } ?>
<? if ($holding->region) { ?>
    <p>Компания имеет штаб-квартиру в регионе: <?= $holding->region->name ?></p>
<? } else { ?>
    <p>Компания не имеет штаб-квартиры</p>
<? } ?>
<h3>Лицензии:</h3>
<? if (count($holding->licenses)) { ?>
    <button class="btn btn-default" id="list_licenses_button" >Свернуть список</button>
    <ul id="list_licenses" style="display: none" >
    <? foreach ($holding->licenses as $license) { ?>
            <li>
            <?= $license->proto->name ?> (<?= $license->state->name ?>)
            </li>
            <? } ?>
    </ul>
    <? } else { ?>
    <p>Компания не обладает лицензией ни на один вид деятельности</p>
<? } ?>
<h3>Недвижимость</h3>
<? if (count($holding->factories)) { ?>
    <ul>
    <? foreach ($holding->factories as $factory) { ?>
            <li>
            <?= Html::a($factory->name, '#', ['onclick' => "load_page('factory-info',{'id':{$factory->id}})"]) ?> 
                <? if ($factory->status < 0) { ?><span style="color:red;">(не достроено, запланированная дата окончания строительства: <span class="formatDate" data-unixtime="<?= $factory->builded ?>"><?= date('d-M-Y H:i', $factory->builded) ?></span>)</span><? } ?>
                <? if ($factory->status > 1) { ?><span style="color:red;">(не работает)</span><? } ?>
            </li>
            <? } ?>
    </ul>
    <? } else { ?>
    <p>Компания не владеет недвижимостью</p>
<? } ?>
<h3>Инфраструктура</h3>
<? if (count($holding->lines)) { ?>
    <ul>
    <? foreach ($holding->lines as $line) { ?>
            <li>
                <?=$line->proto->name?> <?=$line->region1->name?> — <?=$line->region2->name?>
            </li>
            <? } ?>
    </ul>
    <? } else { ?>
    <p>Компания не владеет объектами инфраструктуры</p>
<? } ?>
<h3>Список акционеров:</h3>
<ul>
<? foreach ($holding->stocks as $stock) { ?>
        <li>
            <?=$stock->master->getHtmlName()?> <?= round($stock->getPercents(), 2) ?>%
        </li>
    <? } ?>
</ul>
<h3>Решения на голосовании:</h3>
<? if (count($holding->decisions)) { ?>
    <table class="table">
        <?
        foreach ($holding->decisions as $decision) {
            $data = json_decode($decision->data);
            ?>
            <tr>
                <td><?= date('d-m-Y', $decision->created) ?></td>
                <td><?
                    switch ($decision->decision_type) {
                        case HoldingDecision::DECISION_CHANGENAME:
                            echo 'Переименование компании в «' . $data->new_name . '»';
                            break;
                        case HoldingDecision::DECISION_PAYDIVIDENTS:
                            echo 'Выплата дивидентов в размере ' . $data->sum . ' ' . MyHtmlHelper::icon('money');
                            break;
                        case HoldingDecision::DECISION_GIVELICENSE:
                            $license = LicenseProto::findByPk($data->license_id);
                            $state = State::findByPk($data->state_id);
                            echo 'Получение лицензии на «' . $license->name . '» в государстве ' . $state->name;
                            break;
                        case HoldingDecision::DECISION_BUILDFABRIC:
                            $fType = FactoryProto::findByPk($data->factory_type);
                            $region = Region::findByPk($data->region_id);
                            echo "Строительство нового обьекта: {$fType->name} под названием «{$data->name}» в регионе {$region->name}";
                            break;
                        case HoldingDecision::DECISION_SETMANAGER:
                            $user = User::findByPk($data->uid);
                            $factory = Factory::findByPk($data->factory_id);
                            $region_name = $factory->region->name . ($factory->region->state ? ', ' . $factory->region->state->short_name : '');
                            echo "Назначение человека по имени {$user->name} на должность управляющего обьектом {$factory->name} ({$region_name})";
                            break;
                        case HoldingDecision::DECISION_SETMAINOFFICE:
                            $factory = Factory::findByPk($data->factory_id);
                            $region_name = $factory->region->name . ($factory->region->state ? ', ' . $factory->region->state->short_name : '');
                            echo "Назначение офиса {$factory->name} ({$region_name}) главным офисом компании";
                            break;
                        case HoldingDecision::DECISION_RENAMEFABRIC:
                            $factory = Factory::findByPk($data->factory_id);
                            $region_name = $factory->region->name . ($factory->region->state ? ', ' . $factory->region->state->short_name : '');
                            echo "Переименование объекта {$factory->name} ({$region_name}) в {$data->new_name}";
                            break;
                        case HoldingDecision::DECISION_SELLFACTORY:
                            $factory = Factory::findByPk($data->factory_id);
                            $region_name = $factory->region->name . ($factory->region->state ? ', ' . $factory->region->state->short_name : '');
                            $startPrice = MyHtmlHelper::moneyFormat($data->start_price);
                            $endPrice = ($data->end_price) ? " и стоп-ценой ".MyHtmlHelper::moneyFormat($data->end_price) : '';
                            echo "Продажа объекта {$factory->name} ({$region_name}) с начальной ценой ".$startPrice.$endPrice;
                            break;
                        case HoldingDecision::DECISION_SETDIRECTOR:
                            $user = User::findByPk($data->uid);
                            echo "Назначение человека по имени {$user->name} на должность управляющего директора";
                            break;
                        case HoldingDecision::DECISION_BUILDLINE:
                            $lineProto = LineProto::findByPk($data->proto_id);
                            $region1 = Region::findByPk($data->region1_id);
                            $region2 = Region::findByPk($data->region2_id);
                            echo "Строительство объекта «{$lineProto->name}» между регионами {$region1->name} и {$region2->name}";
                            break;
                    }
                    ?></td><td>
                    <?
                    $za = 0;
                    $protiv = 0;
                    foreach ($decision->votes as $vote) {
                        if (intval($vote->variant) === 1) {
                            $za += ($vote->stock) ? $vote->stock->getPercents() : 0;
                        } elseif (intval($vote->variant) === 2) {
                            $protiv += ($vote->stock) ? $vote->stock->getPercents() : 0;
                        }
                    }
                    ?>
                    <span style="color:green"><?= round($za, 2) ?>% акций ЗА</span>, <span style="color:red"><?= round($protiv, 2) ?>% акций ПРОТИВ</span>
                </td>
                <td>
                    <?
                    $allreadyVoted = false;
                    foreach ($decision->votes as $vote) {
                        if ($vote->stock_id === $userStock->id) {
                            $allreadyVoted = true;
                        }
                    }
                    if ($allreadyVoted) {
                        echo "Вы уже проголосовали";
                    } else {
                        ?>
                        <button class="btn btn-green" onclick="vote_for_decision(<?= $decision->id ?>, 1)">ЗА</button>
                        <button class="btn btn-red" onclick="vote_for_decision(<?= $decision->id ?>, 2)">ПРОТИВ</button>
                        <?
                    }
                    ?>
                </td>
            </tr>        
            <?
        }
        ?>
    </table>
<? } else { ?>
    <p>Нет решений на голосовании</p>
<? } ?>

<div class="btn-toolbar">
    <div class="btn-group">
        <button class="btn btn-sm dropdown-toggle btn-sea" data-toggle="dropdown">
            Общие предложения <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <!--<li class="divider"></li>-->
            <li><a href="#" onclick="$('#rename_holding_modal').modal();" >Переименовать холдинг</a></li>
            <li><a href="#" onclick="$('#set_main_office_modal').modal();" >Установить главный офис</a></li>
            <li><a href="#" onclick="$('#set_director_modal').modal();" >Назначить директора</a></li>
        </ul>
    </div>
    <div class="btn-group">
        <button class="btn btn-sm dropdown-toggle btn-green" data-toggle="dropdown">
            Управление счётом <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="#" onclick="$('#stock_dividents_modal').modal();" >Выплатить дивиденты</a></li>
            <li><a href="#" onclick="$('#insert_money_modal').modal();" >Внести деньги на счёт</a></li>
        </ul>
    </div>
    <div class="btn-group">
        <button class="btn btn-sm dropdown-toggle btn-gray" data-toggle="dropdown">
            Управление недвижимостью <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="#" onclick="$('#new_factory_modal').modal();" >Построить новое предприятие</a></li>
            <li><a href="#" onclick="$('#new_line_modal').modal();recalc_build_line_variants();" >Построить новый объект инфраструктуры</a></li>
            <li><a href="#" onclick="$('#rename_factory_modal').modal();" >Переименовать обьект</a></li>
            <li><a href="#" onclick="$('#sell_factory_modal').modal();" >Выставить предприятие на продажу</a></li>
            <li><a href="#" onclick="$('#set_manager_modal').modal();" >Назначить управляющего</a></li>
        </ul>
    </div>
<? if ($holding->state) { ?>
        <div class="btn-group">
            <button class="btn btn-sm dropdown-toggle btn-brown" data-toggle="dropdown">
                Управление лицензиями <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <!--<li class="divider"></li>-->
                <li><a href="#" onclick="$('#new_license_modal').modal();" >Получить лицензию на новый вид деятельности</a></li>
            </ul>
        </div>
    </div>

    <div style="display:none;" class="modal" id="new_license_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel1231">Получение лицензии</h3>
        </div>
        <div id="new_license_modal_body" class="modal-body">
            <div class="control-group">
                <label class="control-label" for="#new_license_state_id">Государство</label>
                <div class="controls">            
                    <select id="new_license_state_id">
                        <?
                        $states = State::find()->all();
                        foreach ($states as $state):
                            ?>
                            <option <? if ($state->id === $holding->state_id): ?> selected="selected" <? endif ?> id="state_option<?= $state->id ?>" value="<?= $state->id ?>" ><?= $state->name ?></option>       
                        <? endforeach ?>
                    </select>
                </div>
                <label class="control-label" for="#new_license_id">Лицензия</label>
                <div class="controls">
                    <select id="new_license_id">
                        <?
                        $licenses = LicenseProto::find()->all();

                        foreach ($licenses as $license) {
                            $stateLicense = null;
                            $allowed = true;
                            foreach ($holding->licenses as $hl) {
                                if ($license->id === $hl->proto_id) {
                                    $allowed = false;
                                    $break;
                                }
                            }
                            if (!$allowed)
                                continue;

                            foreach ($holding->state->licenses as $sl) {
                                if ($license->id === $sl->proto_id) {
                                    $stateLicense = $sl;
                                    break;
                                }
                            }
                            $text = "Получение лицензии бесплатно";
                            if (!(is_null($stateLicense))) {
                                if ($stateLicense->is_only_goverment) {
                                    if (!$userStock->master->isGoverment($holding->state)) {
                                        continue;
                                    }
                                }
                                if ($stateLicense->cost) {
                                    $text = number_format($stateLicense->cost, 0, '', ' ') . ' ' . MyHtmlHelper::icon('money');
                                }
                                if ($stateLicense->is_need_confirm) {
                                    $text .= "<br>Необходимо подтверждение министра";
                                }
                            }
                                ?>
                                <option id="license_option<?= $license->id ?>" value="<?= $license->id ?>" data-text="<?= $text ?>"><?= $license->name ?></option>      
                                <? 
                            } ?>
                    </select>
                </div>
                <p id="license_info"></p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" data-dismiss="modal"  onclick="get_new_license(<?= $holding->id ?>)">Получить</button>
            <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
        </div>
    </div>
<? } else { ?>
    <p style="color:red;">Компания зарегистрирована в несущесвующем ныне государстве!</p>
<? } ?>
<div style="display:none;" class="modal" id="insert_money_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1232">Внесение денег на счёт</h3>
    </div>
    <div id="insert_money_modal_body" class="modal-body">
        <div class="control-group">
            <label class="control-label" for="#insert_sum">Сумма для внесения на счёт</label>
            <div class="controls">
                <input type="number" id="insert_sum" value="0"> <?= MyHtmlHelper::icon('money') ?>
            </div>
        </div>
        <p>Деньги будут сняты с вашего счёта и внесены на баланс компании. Снять их будет проблематично, если вы не владеете 100% акций.</p>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal"  onclick="insert_money(<?= $holding->id ?>)">Внести</button>
        <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>
<div style="display:none;" class="modal" id="stock_dividents_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1232">Выплата дивидентов акционерам</h3>
    </div>
    <div id="stock_dividents_modal_body" class="modal-body">
        <div class="control-group">
            <label class="control-label" for="#dividents_sum">Сумма для выплаты</label>
            <div class="controls">
                <input type="number" id="dividents_sum" value="0"> <?= MyHtmlHelper::icon('money') ?>
            </div>
        </div>
        <p>Деньги будут выплачены со счёта компании акционерам в долях, равных их долям в компании.</p>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal"  onclick="pay_dividents(<?= $holding->id ?>)">Выплатить</button>
        <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>
<div style="display:none;" class="modal" id="set_manager_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1432" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1233">Назначение нового управляющего</h3>
    </div>
    <div id="set_manager_modal_body" class="modal-body">
        <div class="control-group">
            <label class="control-label" for="#new_manager_factory">Объект недвижимсти:</label>
            <div class="controls">
                <select id="new_manager_factory">
<? foreach ($holding->factories as $factory) { ?>
                        <option value="<?= $factory->id ?>"><?= $factory->name ?> (<?= $factory->region->name ?>)</option>
                    <? } ?>
                </select>
            </div>
            <label class="control-label" for="#new_manager_uid">Новый управляющий:</label>
            <div class="controls">
                <select id="new_manager_uid">
                    <? foreach ($holding->stocks as $stock) { ?>
                        <?
                        switch (get_class($stock->master)) {
                            case 'app\models\User':
                                echo "<option value='{$stock->master->id}'>" . Html::a(Html::img($stock->master->photo, ['style' => 'width:20px']) . ' ' . $stock->master->name, "#", ['onclick' => "load_page('profile',{'uid':{$stock->master->id}})"]) . "</option>";
                                break;
                            case 'app\models\Post':
                                echo "<option value='{$stock->master->user->id}'>" . Html::a(Html::img($stock->master->user->photo, ['style' => 'width:20px']) . ' ' . $stock->master->user->name, "#", ['onclick' => "load_page('profile',{'uid':{$stock->master->user->id}})"]) . "</option>";
                                break;
                            case 'app\models\Holding':

                                break;
                        }
                        ?>
<? } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal"  onclick="json_request('new-holding-decision', {'holding_id':<?= $holding->id ?>, 'factory_id': $('#new_manager_factory').val(), 'uid': $('#new_manager_uid').val(), 'type': 6})">Назначить</button>
        <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>
<div style="display:none;" class="modal" id="set_director_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabelsdm" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabelsdm">Назначение нового директора</h3>
    </div>
    <div id="set_manager_modal_body" class="modal-body">
        <div class="control-group">
            <label class="control-label" for="#new_director_uid">Новый директор:</label>
            <div class="controls">
                <select id="new_director_uid">
                    <? foreach ($holding->stocks as $stock) { ?>
                        <?
                        switch (get_class($stock->master)) {
                            case 'app\models\User':
                                echo "<option value='{$stock->master->id}'>" . Html::a(Html::img($stock->master->photo, ['style' => 'width:20px']) . ' ' . $stock->master->name, "#", ['onclick' => "load_page('profile',{'uid':{$stock->master->id}})"]) . "</option>";
                                break;
                            case 'app\models\Post':
                                echo "<option value='{$stock->master->user->id}'>" . Html::a(Html::img($stock->master->user->photo, ['style' => 'width:20px']) . ' ' . $stock->master->user->name, "#", ['onclick' => "load_page('profile',{'uid':{$stock->master->user->id}})"]) . "</option>";
                                break;
                            case 'app\models\Holding':

                                break;
                        }
                        ?>
<? } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal"  onclick="json_request('new-holding-decision', {'holding_id':<?= $holding->id ?>, 'uid': $('#new_director_uid').val(), 'type': <?=HoldingDecision::DECISION_SETDIRECTOR?>})">Назначить</button>
        <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>
<div style="display:none;" class="modal" id="rename_holding_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1234">Переименование компании</h3>
    </div>
    <div id="rename_holding_modal_body" class="modal-body">
        <div class="control-group">
            <label class="control-label" for="#holding_new_name">Название</label>
            <div class="controls">
                <input type="text" id="holding_new_name" value="<?= htmlspecialchars($holding->name) ?>">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal"  onclick="rename_holding(<?= $holding->id ?>)">Переименовать</button>
        <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>

<div style="display:none;" class="modal" id="rename_factory_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1234">Переименование обьекта</h3>
    </div>
    <div id="rename_factory_modal_body" class="modal-body">
        <div class="control-group">
            <label class="control-label" for="#factory_id_for_rename">Обьект</label>
            <div class="controls">
                <select id="factory_id_for_rename">
<? foreach ($holding->factories as $factory) { ?>
                        <option value="<?= $factory->id ?>"><?= $factory->name ?> (<?= $factory->region->name ?>)</option>
<? } ?>
                </select>
            </div>
            <label class="control-label" for="#factory_new_name">Название</label>
            <div class="controls">
                <input type="text" id="factory_new_name" value="">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal"  onclick="rename_factory()">Переименовать</button>
        <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>

<div style="display:none;" class="modal" id="sell_factory_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabelsell_factory_modal" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel123sell_factory_modal">Выставление на продажу</h3>
    </div>
    <div id="rename_factory_modal_body" class="modal-body">
        <div class="control-group">
            <label class="control-label" for="#factory_id_for_sell">Обьект</label>
            <div class="controls">
                <select id="factory_id_for_sell">
                    <? foreach ($holding->factories as $factory): ?>
                        <option value="<?= $factory->id ?>"><?= $factory->name ?> (<?= $factory->region->name ?>)</option>
                    <? endforeach ?>
                </select>
            </div>
            <label class="control-label" for="#factory_start_price">Начальная цена</label>
            <div class="controls">
                <input type="number" id="factory_start_price" value=""> <?=MyHtmlHelper::icon('money')?>
            </div>
            <label class="control-label" for="#factory_end_price">Стоп-цена</label>
            <div class="controls">
                <input type="number" id="factory_end_price" value=""> <?=MyHtmlHelper::icon('money')?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal"  onclick="sell_factory()">Выставить на продажу</button>
        <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>

<div style="display:none;" class="modal" id="set_main_office_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel1234">Установка главного офиса</h3>
    </div>
    <div id="set_main_office_modal_body" class="modal-body">
        <div class="control-group">
            <label class="control-label" for="#new_main_office_id">Обьект</label>
            <div class="controls">
                <select id="new_main_office_id">
<? foreach ($holding->factories as $factory) {
    if ($factory->proto_id == 4) {
        ?>
                            <option value="<?= $factory->id ?>"><?= $factory->name ?> (<?= $factory->region->name ?>)</option>
    <? }
}
?>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal"  onclick="set_main_office()">Установить</button>
        <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>

<div style="display:none;" class="modal" id="new_factory_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabelnew_factory_modal" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Строительство</h3>
    </div>
    <div id="new_factory_modal_body" class="modal-body">

        <div class="control-group" >

            <label class="control-label" for="#factory_new_region">Место строительства</label>
            <div class="controls">
                <select id="factory_new_region">
<?
$regions = Region::find()->with('state')->orderBy('state_id')->all();
foreach ($regions as $i => $region) {
    ?>
    <? if ($i == 0 || $regions[$i - 1]->state_id != $region->state_id) { ?>
        <?= ($i) ? '</optgroup>' : '' ?><optgroup label="<?= ($region->state) ? $region->state->name : 'Ничейные регионы' ?>">
    <? } ?>
                            <option value="<?= $region->id ?>" <?= ($region->id == $holding->region_id) ? "selected='selected'" : '' ?>><?= $region->name ?></option>
<? } ?>
                </select>
            </div>
        </div>


    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" id="build_fabric_page2" >Далее</button>
        <button style="display:none;" class="btn btn-primary" data-dismiss="modal" id="start_build" onclick="start_build()">Начать строительство</button>
        <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>

<div style="display:none;" class="modal" id="new_line_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabelnew_line_modal" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Строительство</h3>
    </div>
    <div id="new_line_modal_body" class="modal-body">

        <div class="control-group" >

            <label class="control-label" for="#line_new_proto_id">Тип</label>
            <div class="controls">
                <select id="line_new_proto_id">
                    <? $protos = LineProto::find()->all();
                    foreach ($protos as $proto): ?>
                    <option data-cost="<?=$proto->build_cost?>" value="<?=$proto->id?>"><?=$proto->name?></option>
                    <? endforeach ?>
                </select>
            </div>
        </div>

        <div class="control-group" >

            <label class="control-label" for="#line_new_region1">Откуда</label>
            <div class="controls">
                <select id="line_new_region1">
<?
foreach ($regions as $i => $region) {
    ?>
    <? if ($i == 0 || $regions[$i - 1]->state_id != $region->state_id) { ?>
        <?= ($i) ? '</optgroup>' : '' ?><optgroup label="<?= ($region->state) ? $region->state->name : 'Ничейные регионы' ?>">
    <? } ?>
                            <option value="<?= $region->id ?>" <?= ($region->id == $holding->region_id) ? "selected='selected'" : '' ?>><?= $region->name ?></option>
<? } ?>
                </select>
            </div>
        </div>

        <div class="control-group" >

            <label class="control-label" for="#line_new_region2">Куда</label>
            <div class="controls">
                <select id="line_new_region2" disabled="disabled" >
                    <option>...</option>
                </select>
            </div>
        </div>
        
        <div class="help-block">
            Стоимость: <span id="line_new_cost_sum">0</span> <?=MyHtmlHelper::icon('money')?>
        </div>


    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" onclick="start_build_line()" >Начать строительство</button>
        <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>
        </div>
    </div>
</div>

<script>
    function recalc_build_line_variants() {
        get_html('build-line-variants',{'proto_id':$('#line_new_proto_id').val(),'region1_id':$('#line_new_region1').val()},function(html){
            $('#line_new_region2').html(html);
            $('#line_new_region2').removeAttr("disabled");
            recalc_build_line_cost();
        })
    }
    
    function recalc_build_line_cost() {
        var cost = parseFloat($('#line_new_proto_id').find(':selected').data('cost')),
            dist = parseFloat($('#line_new_region2').find(':selected').data('distance'));
            
        $('#line_new_cost_sum').text(number_format(cost*dist,0,'.',' '));
    }
    
    function rename_holding(id) {
        if ($('#holding_new_name').val()) {
            json_request('new-holding-decision', {'holding_id': id, 'type': 1, 'new_name': $('#holding_new_name').val()});
        }
    }

    function vote_for_decision(id, variant) {
        json_request('vote-for-decision', {'decision_id': id, 'variant': variant});
    }

    function pay_dividents(id) {
        if ($('#dividents_sum').val()) {
            json_request('new-holding-decision', {'holding_id': id, 'type': 2, 'sum': $('#dividents_sum').val()});
        }
    }

    function insert_money(id) {
        if ($('#insert_sum').val()) {
            if (confirm("Вы действительно безвозмездно внести деньги на счёт фирмы?")) {
                json_request('insert-money-to-holding', {'holding_id': id, 'sum': $('#insert_sum').val()});
            }
        }
    }

    function get_new_license(id) {
        json_request('new-holding-decision', {'holding_id': id, 'type': 3, 'license_id': $('#new_license_id').val(), 'state_id': $('#new_license_state_id').val()});
    }

    function updateLicenseInfo() {
        $('#license_info').html($("#license_option" + $('#new_license_id').val()).data('text'));
    }

    function rename_factory() {
        json_request('new-holding-decision', {'holding_id':<?= $holding->id ?>, 'type': 8, 'factory_id': $('#factory_id_for_rename').val(), 'new_name': $('#factory_new_name').val()});
    }

    function sell_factory() {
        json_request('new-holding-decision', {
            'holding_id': <?= $holding->id ?>,
            'type': <?=HoldingDecision::DECISION_SELLFACTORY?>,
            'factory_id': $('#factory_id_for_sell').val(),
            'start_price': $('#factory_start_price').val(),
            'end_price': $('#factory_end_price').val()
        });
    }

    function set_main_office() {
        json_request('new-holding-decision', {'holding_id':<?= $holding->id ?>, 'type': 7, 'factory_id': $('#new_main_office_id').val()});
    }

    var new_factory_type = 0;


    function start_build() {
        var cost = parseInt($('#factory_new_size').val()) * parseInt($('#new_factory_type' + new_factory_type).attr("data-buildCost"));
        if (cost > <?= $holding->balance ?>) {
            alert("На счету фирмы недостаточно денег для строительства");
        } else {
            json_request('new-holding-decision', {
                'holding_id':<?= $holding->id ?>,
                'type': 5,
                'name': $('#new_factory_name').val(),
                'region_id': $('#factory_new_region').val(),
                'factory_type': new_factory_type,
                'size': $('#factory_new_size').val()
            });
        }
    }

    function start_build_line() {
        var cost = parseFloat($('#line_new_proto_id').find(':selected').data('cost')),
            dist = parseFloat($('#line_new_region2').find(':selected').data('distance'));
    
        if (Math.round(cost*dist) > <?= $holding->balance ?>) {
            alert("На счету фирмы недостаточно денег для строительства");
        } else {
            json_request('new-holding-decision', {
                'holding_id':<?= $holding->id ?>,
                'type': <?=HoldingDecision::DECISION_BUILDLINE?>,
                'region1_id': $('#line_new_region1').val(),
                'region2_id': $('#line_new_region2').val(),
                'proto_id': $('#line_new_proto_id').val(),
            });
        }
    }

    $(function () {
        updateLicenseInfo();
        $('#new_license_id').change(updateLicenseInfo);
        $('#new_license_state_id').change(function(){
            $('#new_license_id').attr('disabled','disabled');
            get_html('licenses-options',{'state_id':$(this).val(),'holding_id':<?=$holding->id?>},function(data){
                $('#new_license_id').html(data);
                $('#new_license_id').removeAttr('disabled');
                updateLicenseInfo();
            });
        })

        $('#dividents_sum').change(function () {
            if ($(this).val() <=<?= count($holding->stocks) ?>) {
                $(this).val(<?= count($holding->stocks) ?>);
            }
            if ($(this).val() ><?= $holding->balance ?>) {
                $(this).val(<?= $holding->balance ?>);
            }
        });

        $('#list_licenses_button').click(function () {
            if ($(this).val() === 'Развернуть список') {
                $(this).val('Свернуть список');
                $('#list_licenses').slideDown();
            } else {
                $(this).val('Развернуть список');
                $('#list_licenses').slideUp();
            }
        });

        $('#build_fabric_page2').click(function () {
            $(this).remove();
            load_modal('build-fabric', {
                'region_id': $('#factory_new_region').val(),
                'holding_id':<?= $holding->id ?>
            }, 'new_factory_modal', 'new_factory_modal_body');
        });
        
        $('#line_new_region1').change(recalc_build_line_variants);
        
        $('#line_new_region2').change(recalc_build_line_cost);
        $('#line_new_proto_id').change(recalc_build_line_cost);
    });
</script>