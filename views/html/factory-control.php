<?php
/* @var $factory app\models\factories\Factory */
use app\components\MyHtmlHelper,
    app\models\resources\proto\ResourceProto;
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
                        <?php foreach ($factory->getDealings(5) as $dealing): ?>
                            <?php
                                $d = $dealing->getMyBalanceDelta($factory->unnp);
                                $isSender = $dealing->isSender($factory->unnp);
                                $items = json_decode($dealing->items);
                            ?>
                            <tr>
                                <td><i class="icon-time"></i> <span class="formatDateCustom" data-timeformat="HH:mm" data-unixtime="<?=$dealing->time?>"><?=date("H:i",$dealing->time)?></span></td>
                                <td>
                                    <?php if (count($items)): ?>
                                        <i class="icon-<?=$isSender?"minus":"plus"?>"></i>
                                        <?php foreach ($items as $item): ?>
                                        <?php if ($item->type === 'resource'): ?>
                                        <?=$item->count?> <?=ResourceProto::findByPk($item->proto_id)->icon?>
                                        <?php endif ?>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <?php if ($isSender): ?>
                                            <?=$dealing->recipient?$dealing->recipient->getHtmlName():'unknown'?>
                                        <?php else: ?>
                                            <?=$dealing->sender?$dealing->sender->getHtmlName():'unknown'?>
                                        <?php endif ?>
                                    <?php endif ?>
                                </td>
                            <?php if ($d > 0): ?>
                                <td class="status-success"><i class="icon-arrow-up"></i> <?=MyHtmlHelper::moneyFormat($d)?></td>
                            <?php else: ?>
                                <td class="status-error"><i class="icon-arrow-down"></i> <?=MyHtmlHelper::moneyFormat($d)?></td>
                            <?php endif ?>
                            </tr>
                        <?php endforeach ?>
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
                        <?php if (count($factory->resourceCosts)): ?>
                        <thead>
                            <tr>
                              <td>Ресурс</td>
                              <td>Кому доступна цена</td>
                              <td style="min-width:80px">Цена</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($factory->resourceCosts as $cost): ?>
                            <tr>
                                <td><?= $cost->resource->proto->icon ?> <?= $cost->resource->proto->name ?></td>
                                <td><?= $cost->getHtmlType()?></td>
                                <td><?= number_format($cost->cost, 2, '.', ' ') ?> <?= MyHtmlHelper::icon("money") ?></td>
                            </tr>
                        <?php endforeach;
                        else:?>
                        <tbody>
                            <tr>
                                <td colspan="2" style="text-align:center">Цены не установлены</td>
                            </tr>
                        <?php endif ?>
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
                <?php if (count($factory->autobuySettings)): ?>
                <?php foreach ($factory->autobuySettings as $settings): ?>
                    <p>
                        Закупка <?=number_format($settings->count,0,'',' ')?> <?=$settings->resourceProto->icon?> в час
                        по цене не выше <?=MyHtmlHelper::moneyFormat($settings->max_cost)?>
                        качества не ниже <?=MyHtmlHelper::oneTen2Stars($settings->min_quality)?>
                        <? if ($settings->state_id): ?>
                            только у налогоплательщиков страны <?=$settings->state->getHtmlName()?>
                        <? endif ?>
                        <? if ($settings->holding_id): ?>
                            только у предприятий компании <?=$settings->holding->getHtmlName()?>
                        <? endif ?>
                    </p>
                <?php endforeach ?>
                <?php else: ?>
                    <p>Автозакупка отключена</p>
                <?php endif ?>
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
                        <?php foreach ($factory->proto->resources as $rpk): if ($rpk->resourceProto->isStorable()): ?>
                            <tr>
                                <td><?= $rpk->resourceProto->icon ?> <?= $rpk->resourceProto->name ?></td>
                                <td><?= number_format($factory->storageSize($rpk->resourceProto->id), 0, '', ' ') ?> <?= $rpk->resourceProto->icon ?></td>
                            </tr>
                        <?php endif;
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
                        <?php foreach ($factory->content as $store): if ($store->proto->isStorable()): ?>
                            <tr>
                                <td><?= $store->proto->icon ?> <?= $store->proto->name ?></td>
                                <td><?= number_format($store->count, 0, '', ' ') ?> <?= $store->proto->icon ?></td>
                                <td><?= $store->count ? MyHtmlHelper::oneTen2Stars($store->quality) : '' ?></td>
                            </tr>
                        <?php endif;
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
                        <?php if (count($factory->proto->import)): ?>
                        <thead>
                            <tr>
                                <td colspan="2">Расход ресурсов</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($factory->proto->import as $kit): ?>
                            <tr>
                                <td><?= $kit->resourceProto->name ?> </td>
                                <td><?= number_format($kit->count*$factory->size, 0, '', ' ') ?> <?= $kit->resourceProto->icon ?></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                        <?php endif ?>
                        <?php if (count($factory->proto->export)): ?>
                        <thead>
                            <tr>
                                <td colspan="2">Производство ресурсов</td>
                            </tr>
                        </thead>                        
                        <tbody>
                        <?php foreach ($factory->proto->export as $kit): ?>
                            <tr>
                                <td><?= $kit->resourceProto->name ?> </td>
                                <td><?= number_format($kit->count*$factory->size, 0, '', ' ') ?> <?= $kit->resourceProto->icon ?></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
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
                        <?php foreach ($factory->proto->workers as $tWorker): ?>
                            <?php $salary = $factory->getSalaryByClass($tWorker->pop_class_id) ?>
                            <tr>
                                <td><?=$tWorker->popClass->name ?></td>
                                <td style="text-align: center;"><?=$factory->getWorkersCountByClass($tWorker->pop_class_id)?></td>
                                <td style="text-align: center;"><?=$factory->getNeedWorkersCountByClass($tWorker->pop_class_id)?></td>
                                <td style="text-align: center;"><?=$salary ? $salary.' '.MyHtmlHelper::icon('money') : '—'?></td>
                            </tr>
                        <?php endforeach ?>
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
                        <li><a href="#" onclick="$('#resources_selling_first').modal();" >Продажа ресурсов</a></li>
                        <li><a href="#" onclick="$('#resources_autobuy_first').modal();" >Автозакупка ресурсов</a></li>
                    </ul>
                </div>
                <?php if ($factory->status === app\models\factories\Factory::STATUS_STOPPED): ?>
                <button class="btn btn-green" onclick="if (confirm('Вы действительно возобновить работу предприятия?')) {json_request('manager-factory-start-work',{'id':<?=$factory->id?>})}">Возобновить работу</button>
                <?php else: ?>
                <button class="btn btn-red" onclick="if (confirm('Вы действительно хотите уволить всех рабочих и остановить работу?')) {json_request('manager-factory-stop-work',{'id':<?=$factory->id?>})}">Остановить работу</button>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>



<div style="display:none" class="modal fade" id="resources_selling_first" tabindex="-1" role="dialog" aria-labelledby="resources_selling_first_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="resources_selling_first_label">Выставить на продажу</h3>
            </div>
            <div id="resources_selling_first_body" class="modal-body">
                <h3>Ресурсы:</h3>
                <select id="resource_proto_id_for_selling">
                <?php foreach ($factory->proto->export as $kit): ?>
                    <option value="<?=$kit->resource_proto_id?>"><?= $kit->resourceProto->name ?></option>
                <?php endforeach ?>
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn btn-green" onclick="load_modal('manager-factory-set-resource-selling',{'factory_id':<?=$factory->id?>,'resource_proto_id':$('#resource_proto_id_for_selling').val()},'resources_selling_second','resources_selling_second_body')">Продолжить</button>
                <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>

<div style="display:none" class="modal fade" id="resources_selling_second" tabindex="-1" role="dialog" aria-labelledby="resources_selling_second_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="resources_selling_second_label">Выставить на продажу</h3>
            </div>
            <div id="resources_selling_second_body" class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-green" onclick="json_request('save-resource-cost',{'resource_id':$('#resource_for_selling_id').val(),'cost':$('#resource_for_selling_cost').val(),'type':$('#form_resource_selling_cost input[name=resource_for_selling_type]:checked').val()})">Сохранить</button>
                <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>

<div style="display:none" class="modal fade" id="resources_autobuy_first" tabindex="-1" role="dialog" aria-labelledby="resources_autobuy_first_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="resources_autobuy_first_label">Установка автозакупки</h3>
            </div>
            <div id="resources_autobuy_first_body" class="modal-body">
                <h3>Ресурсы:</h3>
                <select id="resource_proto_id_for_autobuy">
                <?php foreach ($factory->proto->import as $kit): ?>
                    <option value="<?=$kit->resource_proto_id?>"><?= $kit->resourceProto->name ?></option>
                <?php endforeach ?>
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn btn-green" onclick="load_modal('manager-factory-set-resource-autobuy',{'factory_id':<?=$factory->id?>,'resource_proto_id':$('#resource_proto_id_for_autobuy').val()},'resources_autobuy_second','resources_autobuy_second_body')">Продолжить</button>
                <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div></div>
</div>

<div style="display:none" class="modal fade" id="resources_autobuy_second" tabindex="-1" role="dialog" aria-labelledby="resources_autobuy_second_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="resources_autobuy_second_label">Установка автозакупки</h3>
            </div>
            <div id="resources_autobuy_second_body" class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-green" onclick="json_request('save-autobuy-settings',{'resource_id':$('#resource_for_autobuy_id').val(),'autobuy':$('#resource_autobuy_on').is(':checked')?1:0,'cost':$('#resource_for_autobuy_cost').val(),'quality':$('#resource_for_autobuy_quality').val(),'type':$('#form_resource_autobuy_settings input[name=resource_for_autobuy_type]:checked').val()})">Сохранить</button>
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
                    <?php
                    foreach ($factory->proto->workers as $tWorker):
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
                    <?php endforeach ?>
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
        <?php foreach ($factory->proto->workers as $tWorker): ?>
            data.salary_<?= $tWorker->pop_class_id ?> = $('#salary_<?= $tWorker->pop_class_id ?>').val();
        <?php endforeach ?>
        json_request('factory-manager-salaries-save', data);
    }


</script>