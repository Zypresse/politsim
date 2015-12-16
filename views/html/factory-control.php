<?php
/* @var $factory app\models\factories\Factory */
use app\components\MyHtmlHelper,
    app\models\resurses\proto\ResurseProto;
?>
<div class="container">
    <div class="row">
        <div class="col-md-7">
            <h2><?= $factory->proto->name ?> &laquo;<?= htmlspecialchars($factory->name) ?>&raquo;</h2>

            <p><strong>Местоположение:</strong> <?= $factory->region->getHtmlName() ?></p>
            <p><strong>Владелец:</strong> <?= $factory->holding->getHtmlName() ?></p>
            <p><strong>Управляющий:</strong> <?= $factory->manager ? $factory->manager->getHtmlName() : "не назначен" ?></p>
            <p><strong>Эффективность работы:</strong> <?= MyHtmlHelper::zeroOne2Stars($factory->eff_region * $factory->eff_workers) ?> (регион: <?=MyHtmlHelper::zeroOne2Stars($factory->eff_region)?>; рабочие: <?=MyHtmlHelper::zeroOne2Stars($factory->eff_workers)?>)</p>
            <p><strong>Лицевой счёт:</strong> <?=MyHtmlHelper::moneyFormat($factory->balance)?></p>
            <p>
                <strong>Статус:</strong> <?= $factory->statusName ?> 
                <? if ($factory->status < 0) { ?>
                    (запланированная дата окончания строительства: 
                    <span class="formatDate" data-unixtime="<?= $factory->builded ?>">
                        <?= date('d-M-Y H:i', $factory->builded) ?>
                    </span>)
                <? } ?>
            </p>
        </div>
        <div class="col-md-5">
            <div class="box" style="margin-top: 10px">
                <div class="box-header">
                    <span class="title"><i class="icon-money"></i> Финансы</span>

                    <ul class="box-toolbar">
                        <li><button class="btn btn-xs btn-lightblue" onclick="load_modal('factory-dealings',{'id':<?=$factory->id?>},'factory_dealings','factory_dealings_body')">Все сделки</button></li>
                    </ul>
                </div>
                <div class="box-content">    
                    <table class="table table-normal">
                        <tbody>
                        <? foreach ($factory->getDealings(5) as $dealing): ?>
                            <? $d = $dealing->getMyBalanceDelta($factory->unnp) ?>
                            <? $isSender = $dealing->isSender($factory->unnp) ?>
                            <? $items = json_decode($dealing->items) ?>
                            <tr>
                                <td><i class="icon-time"></i> <span class="formatDateCustom" data-timeformat="HH:mm" data-unixtime="<?=$dealing->time?>"><?=date("H:i",$dealing->time)?></span></td>
                                <td>
                                    <? if (count($items)): ?>
                                        <i class="icon-<?=$isSender?"minus":"plus"?>"></i>
                                        <? foreach ($items as $item): ?>
                                        <? if ($item->type === 'resurse'): ?>
                                        <?=$item->count?> <?=MyHtmlHelper::icon(ResurseProto::findByPk($item->proto_id)->class_name)?>
                                        <? endif ?>
                                        <? endforeach ?>
                                    <? else: ?>
                                        <?=$isSender?$dealing->recipient->getHtmlName():$dealing->sender->getHtmlName()?>
                                    <? endif ?>
                                </td>
                            <? if ($d > 0): ?>
                                <td class="status-success"><i class="icon-arrow-up"></i> <?=MyHtmlHelper::moneyFormat($d)?></td>
                            <? else: ?>
                                <td class="status-error"><i class="icon-arrow-down"></i> <?=MyHtmlHelper::moneyFormat($d)?></td>
                            <? endif ?>
                            </tr>
                        <? endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="box">
                <div class="box-header">
                    <span class="title"><i class="icon-money"></i> Настройки продажи</span>
                </div>
                <div class="box-content">
                    <table class="table table-normal">
                        <? if (count($factory->resurseCosts)): ?>
                        <thead>
                            <tr>
                              <td>Ресурс</td>
                              <td>Кому доступна цена</td>
                              <td style="min-width:80px">Цена</td>
                            </tr>
                        </thead>
                        <tbody>
                        <? foreach ($factory->resurseCosts as $cost): ?>
                            <tr>
                                <td><?= MyHtmlHelper::icon($cost->resurse->proto->class_name) ?> <?= $cost->resurse->proto->name ?></td>
                                <td><?= $cost->getHtmlType()?></td>
                                <td><?= number_format($cost->cost, 2, '.', ' ') ?> <?= MyHtmlHelper::icon("money") ?></td>
                            </tr>
                        <? endforeach;
                        else:?>
                        <tbody>
                            <tr>
                                <td colspan="2" style="text-align:center">Цены не установлены</td>
                            </tr>
                        <? endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="box">
                <div class="box-header">
                    <span class="title"><i class="icon-money"></i> Автозакупка</span>
                </div>
                <div class="box-content padded">
                <? if (count($factory->autobuySettings)): ?>
                <? foreach ($factory->autobuySettings as $settings): ?>
                    <p>
                        Закупка <?=number_format($settings->count,0,'',' ')?> <?=MyHtmlHelper::icon($settings->resurseProto->class_name)?> в час
                        по цене не выше <?=MyHtmlHelper::moneyFormat($settings->max_cost)?>
                        качества не ниже <?=MyHtmlHelper::oneTen2Stars($settings->min_quality)?>
                        <? if ($settings->state_id): ?>
                            только у налогоплательщиков страны <?=$settings->state->getHtmlName()?>
                        <? endif ?>
                        <? if ($settings->holding_id): ?>
                            только у предприятий компании <?=$settings->holding->getHtmlName()?>
                        <? endif ?>
                    </p>
                <? endforeach ?>
                <? else: ?>
                    <p>Автозакупка отключена</p>
                <? endif ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="box">
                <div class="box-header">
                    <span class="title"><i class="icon-home"></i> Размеры складов</span>
                </div>
                <div class="box-content">
                    <table class="table table-normal">
                        <thead>
                            <tr>
                              <td>Ресурс</td>
                              <td>Размер</td>
                            </tr>
                        </thead>
                        <tbody>
                        <? foreach ($factory->proto->resurses as $rpk): if ($rpk->resurseProto->isStorable()): ?>
                            <tr>
                                <td><?= MyHtmlHelper::icon($rpk->resurseProto->class_name) ?> <?= $rpk->resurseProto->name ?></td>
                                <td><?= number_format($factory->storageSize($rpk->resurseProto->id), 0, '', ' ') ?> <?= MyHtmlHelper::icon($rpk->resurseProto->class_name) ?></td>
                            </tr>
                        <? endif;
                        endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="box">
                <div class="box-header">
                    <span class="title"><i class="icon-home"></i> Ресурсы на складах</span>
                </div>
                <div class="box-content">
                    <table class="table table-normal">
                        <thead>
                            <tr>
                              <td style="">Ресурс</td>
                              <td style="min-width: 60px;">Количество</td>
                              <td style="min-width: 50px;">Качество</td>
                            </tr>
                        </thead>
                        <tbody>
                        <? foreach ($factory->content as $store): if ($store->proto->isStorable()): ?>
                            <tr>
                                <td><?= MyHtmlHelper::icon($store->proto->class_name) ?> <?= $store->proto->name ?></td>
                                <td><?= number_format($store->count, 0, '', ' ') ?> <?= MyHtmlHelper::icon($store->proto->class_name) ?></td>
                                <td><?= $store->count ? MyHtmlHelper::oneTen2Stars($store->quality) : '' ?></td>
                            </tr>
                        <? endif;
                        endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box">
                <div class="box-header">
                    <span class="title"><i class="icon-cog"></i> Производство (в час)</span>
                </div>
                <div class="box-content">
                    <table class="table table-normal">
                        <? if (count($factory->proto->import)): ?>
                        <thead>
                            <tr>
                                <td colspan="2">Расход ресурсов</td>
                            </tr>
                        </thead>
                        <tbody>
                        <? foreach ($factory->proto->import as $kit): ?>
                            <tr>
                                <td><?= $kit->resurseProto->name ?> </td>
                                <td><?= number_format($kit->count*$factory->size, 0, '', ' ') ?> <?= MyHtmlHelper::icon($kit->resurseProto->class_name) ?></td>
                            </tr>
                        <? endforeach ?>
                        </tbody>
                        <? endif ?>
                        <? if (count($factory->proto->export)): ?>
                        <thead>
                            <tr>
                                <td colspan="2">Производство ресурсов</td>
                            </tr>
                        </thead>                        
                        <tbody>
                        <? foreach ($factory->proto->export as $kit): ?>
                            <tr>
                                <td><?= $kit->resurseProto->name ?> </td>
                                <td><?= number_format($kit->count*$factory->size, 0, '', ' ') ?> <?= MyHtmlHelper::icon($kit->resurseProto->class_name) ?></td>
                            </tr>
                        <? endforeach ?>
                        </tbody>
                        <? endif ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <span class="title"><i class="icon-group"></i> Работники</span>
                </div>
                <div class="box-content">
                    <table class="table table-normal">
                        <thead>
                            <tr>
                                <td style="width:40%">Класс</td>
                                <td>Нанято</td>
                                <td>Нужно</td>
                                <td>Зарплата</td>
                            </tr>
                        </thead>
                        <tbody>
                        <? foreach ($factory->proto->workers as $tWorker): ?>
                            <? $salary = $factory->getSalaryByClass($tWorker->pop_class_id) ?>
                            <tr>
                                <td><?=$tWorker->popClass->name ?></td>
                                <td style="text-align: center;"><?=$factory->getWorkersCountByClass($tWorker->pop_class_id)?></td>
                                <td style="text-align: center;"><?=$factory->getNeedWorkersCountByClass($tWorker->pop_class_id)?></td>
                                <td style="text-align: center;"><?=$salary ? $salary.' '.MyHtmlHelper::icon('money') : '—'?></td>
                            </tr>
                        <? endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>Управление</h3>
            <div class="btn-toolbar">
                <div class="btn-group">
                    <button class="btn dropdown-toggle btn-lightblue" data-toggle="dropdown">
                        Персонал <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#" onclick="$('#salaries_manager').modal();" >Зарплаты</a></li>
                    </ul>
                </div>
                <div class="btn-group">
                    <button class="btn dropdown-toggle btn-green" data-toggle="dropdown">
                        Торговля <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#" onclick="$('#resurses_selling_first').modal();" >Продажа ресурсов</a></li>
                        <li><a href="#" onclick="$('#resurses_autobuy_first').modal();" >Автозакупка ресурсов</a></li>
                    </ul>
                </div>
                <button class="btn btn-red" onclick="if (confirm('Вы действительно хотите уволить всех рабочих и остановить работу?')) {json_request('manager-factory-stop-work',{'id':<?=$factory->id?>})}">Остановить работу</button>
            </div>
        </div>
    </div>
</div>



<div style="display:none" class="modal fade" id="resurses_selling_first" tabindex="-1" role="dialog" aria-labelledby="resurses_selling_first_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="resurses_selling_first_label">Выставить на продажу</h3>
            </div>
            <div id="resurses_selling_first_body" class="modal-body">
                <h3>Ресурсы:</h3>
                <select id="resurse_proto_id_for_selling">
                <? foreach ($factory->proto->export as $kit): ?>
                    <option value="<?=$kit->resurse_proto_id?>"><?= $kit->resurseProto->name ?></option>
                <? endforeach ?>
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn btn-green" onclick="load_modal('manager-factory-set-resurse-selling',{'factory_id':<?=$factory->id?>,'resurse_proto_id':$('#resurse_proto_id_for_selling').val()},'resurses_selling_second','resurses_selling_second_body')">Продолжить</button>
                <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>

<div style="display:none" class="modal fade" id="resurses_selling_second" tabindex="-1" role="dialog" aria-labelledby="resurses_selling_second_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="resurses_selling_second_label">Выставить на продажу</h3>
            </div>
            <div id="resurses_selling_second_body" class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-green" onclick="json_request('save-resurse-cost',{'resurse_id':$('#resurse_for_selling_id').val(),'cost':$('#resurse_for_selling_cost').val(),'type':$('#form_resurse_selling_cost input[name=resurse_for_selling_type]:checked').val()})">Сохранить</button>
                <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>

<div style="display:none" class="modal fade" id="resurses_autobuy_first" tabindex="-1" role="dialog" aria-labelledby="resurses_autobuy_first_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="resurses_autobuy_first_label">Установка автозакупки</h3>
            </div>
            <div id="resurses_autobuy_first_body" class="modal-body">
                <h3>Ресурсы:</h3>
                <select id="resurse_proto_id_for_autobuy">
                <? foreach ($factory->proto->import as $kit): ?>
                    <option value="<?=$kit->resurse_proto_id?>"><?= $kit->resurseProto->name ?></option>
                <? endforeach ?>
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn btn-green" onclick="load_modal('manager-factory-set-resurse-autobuy',{'factory_id':<?=$factory->id?>,'resurse_proto_id':$('#resurse_proto_id_for_autobuy').val()},'resurses_autobuy_second','resurses_autobuy_second_body')">Продолжить</button>
                <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>

<div style="display:none" class="modal fade" id="resurses_autobuy_second" tabindex="-1" role="dialog" aria-labelledby="resurses_autobuy_second_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="resurses_autobuy_second_label">Установка автозакупки</h3>
            </div>
            <div id="resurses_autobuy_second_body" class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-green" onclick="json_request('save-autobuy-settings',{'resurse_id':$('#resurse_for_autobuy_id').val(),'autobuy':$('#resurse_autobuy_on').is(':checked')?1:0,'cost':$('#resurse_for_autobuy_cost').val(),'quality':$('#resurse_for_autobuy_quality').val(),'type':$('#form_resurse_autobuy_settings input[name=resurse_for_autobuy_type]:checked').val()})">Сохранить</button>
                <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>

<div style="display:none" class="modal fade" id="salaries_manager" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123213" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel123213">Управление зарплатами</h3>
            </div>
            <div id="salaries_manager_body" class="modal-body">
                <h3>Вакансии:</h3>
                <dl>
                    <?
                    foreach ($factory->proto->workers as $tWorker) {
                        $actived = 0;
                        foreach ($factory->workers as $worker) {
                            if ($worker->class == $tWorker->pop_class_id) {
                                $actived += $worker->count;
                            }
                        }
                        $salary_value = 1;
                        foreach ($factory->salaries as $salary) {
                            if ($salary->pop_class_id == $tWorker->pop_class_id) {
                                $salary_value = $salary->salary;
                                break;
                            }
                        }
                        ?>
                        <dt><?= $tWorker->popClass->name ?> <?= $actived ?>/<?= $tWorker->count * $factory->size ?></dt>
                        <dd>Зарплата: <input id="salary_<?= $tWorker->pop_class_id ?>" type="number" value="<?= $salary_value ?>" style="width:50px" > <?= MyHtmlHelper::icon('money') ?> в месяц</dd>
<? } ?>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-green" onclick="save_salaries()">Сохранить</button>
                <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>

<div style="display:none" class="modal fade" id="factory_dealings" tabindex="-1" role="dialog" aria-labelledby="factory_dealings_label" aria-hidden="true">
    <div class="modal-dialog" style="width:800px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="factory_dealings_label">Последние сделки предприятия</h3>
            </div>
            <div id="factory_dealings_body" class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>

<script>

    function save_salaries() {
        var data = {
            'factory_id': <?= $factory->id ?>
        }
        <? foreach ($factory->proto->workers as $tWorker) { ?>
            data.salary_<?= $tWorker->pop_class_id ?> = $('#salary_<?= $tWorker->pop_class_id ?>').val();
        <? } ?>
        json_request('factory-manager-salaries-save', data);
    }


</script>