<?php

use app\components\MyHtmlHelper,
    app\models\HoldingDecision,
    app\models\Utr;

/* @var $user app\models\User */
/* @var $holding app\models\Holding */
/* @var $licenses app\models\licenses\License[] */
/* @var $factories app\models\factories\Factory[] */

$userStock = $user->getShareholderStock($holding);
?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h1><?= htmlspecialchars($holding->name) ?></h1>
            <p>Директор: <?= $holding->director ? $holding->director->getHtmlName() : '<em>не назначен</em>' ?></p>
            <p>Капитализация: <?= MyHtmlHelper::moneyFormat($holding->capital) ?></p>
            <p>
                Баланс лицевого счёта: <?= MyHtmlHelper::moneyFormat($holding->balance) ?>
                <button onclick="$('#stock_dividents_modal').modal();" class="btn btn-xs dropdown-toggle btn-success">
                    Выплатить дивиденты
                </button>
                <button onclick="$('#insert_money_modal').modal();" class="btn btn-xs dropdown-toggle btn-primary">
                    Внести деньги на счёт
                </button>
            </p>
            <?php if ($holding->state): ?>
                <p>Компания зарегистрирована в государстве <?= $holding->state->getHtmlName() ?></p>
            <?php else: ?>
                <p class="status-error">Компания зарегистрирована в несущесвующем ныне государстве!</p>
            <?php endif ?>
            <?php if ($holding->region): ?>
                <p>Компания имеет головной офис в городе <?= $holding->region->getCityHtmlName() ?></p>
            <?php endif ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <span class="box-title">
                        <i class="fa fa-group"></i> Акционеры
                    </span>
                </div>
                <div class="box-content">    
                    <table class="table table-normal">
                        <thead>
                            <tr>
                                <td>Владелец</td>
                                <td>Пакет акций</td>
                            </tr>
                        </thead>
                        <?php foreach ($holding->stocks as $stock): ?>
                            <tr>
                                <td><?= $stock->master->getHtmlName() ?></td>
                                <td style="text-align:center"><?= MyHtmlHelper::formateNumberword($stock->count, 'акций', 'акция', 'акции') ?> (<?= round($stock->getPercents(), 2) ?>%)</td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <span class="box-title">
                        <i class="fa fa-legal"></i> Лицензии
                    </span>
                    <div class="box-tools pull-right">
                        <button onclick="load_modal('holding-new-license',{'holding_id':<?=$holding->id?>},'new_license_modal','new_license_modal_body')" class="btn btn-xs dropdown-toggle btn-success">
                            Получить лицензию
                        </button>
                    </div>
                </div>
                <div class="box-content">    
                    <table class="table table-normal">
                        <?php if (count($licenses)): ?>
                            <thead>
                                <tr>
                                    <td>Вид деятельности</td>
                                    <td>Государство</td>
                                </tr>
                            </thead>
                            <?php foreach ($licenses as $license): ?>
                                <tr>
                                    <td><?= $license->proto->name ?></td>
                                    <td><?= $license->state->getHtmlName() ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td>Компания не обладает лицензией ни на один вид деятельности</td>
                            </tr>
                        <?php endif ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <span class="box-title">
                        <i class="fa fa-industry"></i> Недвижимость
                    </span>
                    <div class="box-tools pull-right">
                        <button class="btn btn-xs btn-success" onclick="load_modal('build-factory-select-region',{'holding_id':<?=$holding->id?>},'build-factory-modal','build-factory-modal-body')" >
                            Построить новое предприятие
                        </button>
                    </div>
                </div>
                <div class="box-content">    
                    <table class="table table-normal">
                        <?php if (count($factories)): ?>
                            <thead>
                                <tr>
                                    <td>Предприятие</td>
                                    <td style="min-width:200px">Регион</td>
                                    <td style="min-width:102px">Лицевой счёт</td>
                                    <td>Статус</td>
                                    <td style="min-width:142px">Действия</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($factories as $factory): ?>
                                    <tr>
                                        <td><?= $factory->getHtmlName() ?></td>
                                        <td><?= $factory->region->getHtmlName() ?></td>
                                        <td><?= MyHtmlHelper::moneyFormat($factory->balance) ?></td>
                                        <td style="text-align: center"><?= $factory->getStatusShortName() ?></td>
                                        <td style="text-align: center">
                                            <div class="btn-toolbar">
                                                <div class="btn-group">
                                                    <button title="Переименовать" class="btn btn-xs btn-info" onclick="$('#factory_id_for_rename').val(<?=$factory->id?>); $('#factory_new_name').val('<?=$factory->name?>'); $('#rename_factory_modal').modal();" >
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button title="Выставить на продажу" class="btn btn-xs btn-danger" onclick="$('#factory_id_for_sell').val(<?=$factory->id?>); $('#sell_factory_modal').modal();" >
                                                        <i class="fa fa-money"></i>
                                                    </button>
                                                    <button title="Назначить управляющего" class="btn btn-xs btn-primary" onclick="$('#new_manager_factory').val(<?=$factory->id?>);$('#set_manager_modal').modal();" >
                                                        <i class="fa fa-user"></i>
                                                    </button>
                                                    <button title="Внести деньги на счёт" class="btn btn-xs btn-warning" onclick="$('#transfer_inner_factory_unnp').val(<?=$factory->unnp?>); $('#transfer_money_inner_modal').modal();" >
                                                        <i class="fa fa-money"></i>
                                                    </button>
                                                    <button title="Вывести деньги со счёта" class="btn btn-xs btn-success" onclick="$('#transfer_outer_factory_unnp').val(<?=$factory->unnp?>); $('#transfer_money_outer_modal').modal();" >
                                                        <i class="fa fa-money"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                            <tbody>
                                <tr>
                                    <td>Компания не владеет недвижимостью</td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>Решения на голосовании:</h3>
            <?php if (count($holding->decisions)) { ?>
                <table class="table">
                    <?php
                    foreach ($holding->decisions as $decision) {
                        $data = json_decode($decision->data);
                        ?>
                        <tr>
                            <td>
                                <?= $decision->getHtml() ?>
                            </td>
                            <td style="width:250px">
                                <?php
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
                                <span class="status-success"><?= round($za, 2) ?>% акций ЗА</span>, <span class="status-error"><?= round($protiv, 2) ?>% акций ПРОТИВ</span>
                            </td>
                            <td style="width:200px">
                                <?php
                                $allreadyVoted = false;
                                foreach ($decision->votes as $vote) {
                                    if ($vote->stock_id === $userStock->id) {
                                        $allreadyVoted = true;
                                    }
                                }
                                if ($allreadyVoted) {
                                    echo "<span class='status-success'>Вы уже проголосовали</span>";
                                } else {
                                    ?>
                                    <button class="btn btn-success" onclick="vote_for_decision(<?= $decision->id ?>, 1)">ЗА</button>
                                    <button class="btn btn-danger" onclick="vote_for_decision(<?= $decision->id ?>, 2)">ПРОТИВ</button>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>        
                        <?php
                    }
                    ?>
                </table>
            <?php } else { ?>
                <p>Нет решений на голосовании</p>
            <?php } ?>

            <h4>Новое решение:</h4>
            <div class="btn-toolbar">
                <div class="btn-group">
                    <button onclick="$('#rename_holding_modal').modal();" class="btn btn-info">
                        Переименовать холдинг
                    </button>
                </div>
                <div class="btn-group">
                    <button onclick="$('#set_main_office_modal').modal();" class="btn btn-primary">
                        Установить главный офис
                    </button>
                </div>
                <div class="btn-group">
                    <button onclick="$('#set_director_modal').modal();" class="btn btn-success">
                        Назначить директора
                    </button>
                </div>
                <?php if (!count($factories)): ?>
                <div class="btn-group">
                    <button onclick="json_request('new-holding-decision',{'holding_id':<?= $holding->id ?>, 'type': 14})" class="btn btn-danger">
                        Ликвидировать компанию
                    </button>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</section>
<div style="display:none;" class="modal fade" id="new_license_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel1231">Получение лицензии</h3>
            </div>
            <div id="new_license_modal_body" class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal"  onclick="get_new_license(<?= $holding->id ?>)">Получить</button>
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>
<div style="display:none;" class="modal fade" id="insert_money_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
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
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>
<div style="display:none;" class="modal fade" id="stock_dividents_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
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
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>
<div style="display:none;" class="modal fade" id="transfer_money_inner_modal" tabindex="-1" role="dialog" aria-labelledby="transfer_money_inner_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="transfer_money_inner_modal_label">Перевод денег на внутренний счёт</h3>
            </div>
            <div id="transfer_money_inner_modal_body" class="modal-body">
                <input type="hidden" id="transfer_inner_factory_unnp">
                <div class="control-group">
                    <label class="control-label" for="#transfer_inner_sum">Сумма для перевода</label>
                    <div class="controls">
                        <input type="number" id="transfer_inner_sum" value="0"> <?= MyHtmlHelper::icon('money') ?>
                    </div>
                </div>
                <p>Деньги будут выплачены со счёта компании.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal"  onclick="transfer_money_inner(<?= $holding->id ?>)">Перевести</button>
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>
<div style="display:none;" class="modal fade" id="transfer_money_outer_modal" tabindex="-1" role="dialog" aria-labelledby="transfer_money_outer_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="transfer_money_outer_modal_label">Перевод денег на счёт компании</h3>
            </div>
            <div id="transfer_money_outer_modal_body" class="modal-body">
                <input type="hidden" id="transfer_outer_factory_unnp">
                <div class="control-group">
                    <label class="control-label" for="#transfer_outer_sum">Сумма для перевода</label>
                    <div class="controls">
                        <input type="number" id="transfer_outer_sum" value="0"> <?= MyHtmlHelper::icon('money') ?>
                    </div>
                </div>
                <p>Деньги будут выплачены со счёта предприятия.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal"  onclick="transfer_money_outer(<?= $holding->id ?>)">Перевести</button>
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>
<div style="display:none;" class="modal fade" id="set_manager_modal" tabindex="-1" role="dialog" aria-labelledby="set_manager_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"><div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="set_manager_modal_label">Назначение нового управляющего</h3>
            </div>
            <div id="set_manager_modal_body" class="modal-body">
                <input type="hidden" id="new_manager_factory">
                <div class="control-group">
                    <label class="control-label" for="#new_manager_uid">Новый управляющий:</label>
                    <div class="controls">
                        <select id="new_manager_uid">
                        <?php foreach ($holding->stocks as $stock): ?>
                        <?php if ($stock->master->getUnnpType() === Utr::TYPE_USER): ?>
                            <option value="<?=$stock->master->id?>"><?=$stock->master->name?></option>
                        <?php endif ?>
                        <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal"  onclick="json_request('new-holding-decision', {'holding_id':<?= $holding->id ?>, 'factory_id': $('#new_manager_factory').val(), 'uid': $('#new_manager_uid').val(), 'type': 6})">Назначить</button>
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>
<div style="display:none;" class="modal fade" id="set_director_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabelsdm" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabelsdm">Назначение нового директора</h3>
            </div>
            <div id="set_manager_modal_body" class="modal-body">
                <div class="control-group">
                    <label class="control-label" for="#new_director_uid">Новый директор:</label>
                    <div class="controls">
                        <select id="new_director_uid">
                            <?php foreach ($holding->stocks as $stock): ?>
                            <?php if ($stock->master->getUnnpType() === Utr::TYPE_USER): ?>
                                <option value='<?=$stock->master->id?>'><?=$stock->master->name?></option>
                            <?php endif ?>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal"  onclick="json_request('new-holding-decision', {'holding_id':<?= $holding->id ?>, 'uid': $('#new_director_uid').val(), 'type': <?= HoldingDecision::DECISION_SETDIRECTOR ?>})">Назначить</button>
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>
<div style="display:none;" class="modal fade" id="rename_holding_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
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
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>

<div style="display:none;" class="modal fade" id="rename_factory_modal" tabindex="-1" role="dialog" aria-labelledby="rename_factory_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="rename_factory_modal_label">Переименование обьекта</h3>
            </div>
            <div id="rename_factory_modal_body" class="modal-body">
                <div class="control-group">
                    <input type="hidden" id="factory_id_for_rename" >
                    <label class="control-label" for="#factory_new_name">Название</label>
                    <div class="controls">
                        <input type="text" id="factory_new_name" value="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal"  onclick="rename_factory()">Переименовать</button>
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>

<div style="display:none;" class="modal fade" id="sell_factory_modal" tabindex="-1" role="dialog" aria-labelledby="sell_factory_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="sell_factory_modal_label">Выставление на продажу</h3>
            </div>
            <div id="sell_factory_modal_body" class="modal-body">
                <div class="control-group">
                    <input id="factory_id_for_sell" type="hidden">
                    <label class="control-label" for="#factory_start_price">Начальная цена</label>
                    <div class="controls">
                        <input type="number" id="factory_start_price" value=""> <?= MyHtmlHelper::icon('money') ?>
                    </div>
                    <label class="control-label" for="#factory_end_price">Стоп-цена</label>
                    <div class="controls">
                        <input type="number" id="factory_end_price" value=""> <?= MyHtmlHelper::icon('money') ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal"  onclick="sell_factory()">Выставить на продажу</button>
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<div style="display:none;" class="modal fade" id="set_main_office_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel1234">Установка главного офиса</h3>
            </div>
            <div id="set_main_office_modal_body" class="modal-body">
                <div class="control-group">
                    <label class="control-label" for="#new_main_office_id">Обьект</label>
                    <div class="controls">
                        <select id="new_main_office_id">
                            <?php
                            foreach ($factories as $factory) {
                                if ($factory->proto_id == 4) {
                                    ?>
                                    <option value="<?= $factory->id ?>"><?= $factory->name ?> (<?= $factory->region->name ?>)</option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal"  onclick="set_main_office()">Установить</button>
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<div style="display:none;" class="modal fade" id="build-factory-modal" tabindex="-1" role="dialog" aria-labelledby="build-factory-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="build-factory-modal-label">Постройка предприятия</h3>
            </div>
            <div id="build-factory-modal-body" class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button id="build-factory-modal-main-btn" class="btn btn-primary" data-dismiss="modal" >Установить</button>
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<script>
    
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

    function transfer_money_inner(id) {
        if ($('#transfer_inner_sum').val()) {
            json_request('new-holding-decision', {'holding_id': id, 'type': 12, 'unnp': $('#transfer_inner_factory_unnp').val(), 'sum': $('#transfer_inner_sum').val()});
        }
    }

    function transfer_money_outer(id) {
        if ($('#transfer_outer_sum').val()) {
            json_request('new-holding-decision', {'holding_id': id, 'type': 13, 'unnp': $('#transfer_outer_factory_unnp').val(), 'sum': $('#transfer_outer_sum').val()});
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

    function rename_factory() {
        json_request('new-holding-decision', {'holding_id':<?= $holding->id ?>, 'type': 8, 'factory_id': $('#factory_id_for_rename').val(), 'new_name': $('#factory_new_name').val()});
    }

    function sell_factory() {
        json_request('new-holding-decision', {
            'holding_id': <?= $holding->id ?>,
            'type': <?= HoldingDecision::DECISION_SELLFACTORY ?>,
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
    
    $(function () {

        $('#dividents_sum').change(function () {
            if ($(this).val() <=<?= count($holding->stocks) ?>) {
                $(this).val(<?= count($holding->stocks) ?>);
            }
            if ($(this).val() ><?= $holding->balance ?>) {
                $(this).val(<?= $holding->balance ?>);
            }
        });
        
    });
</script>