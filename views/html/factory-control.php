<?php
/* @var $factory app\models\factories\Factory */
use app\components\MyHtmlHelper;
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2><?= $factory->proto->name ?> &laquo;<?= htmlspecialchars($factory->name) ?>&raquo;</h2>

            <p><strong>Местоположение:</strong> <?= MyHtmlHelper::a($factory->region->name, "show_region({$factory->region_id})") ?></p>
            <p><strong>Эффективность работы:</strong> <?= MyHtmlHelper::zeroOne2Stars($factory->eff_region * $factory->eff_workers) ?></p>
            <p><strong>Владелец:</strong> <?= MyHtmlHelper::a($factory->holding->name, "load_page('holding-info',{'id':{$factory->holding_id}})") ?></p>
            <p><strong>Управляющий:</strong> <?= $factory->manager ? MyHtmlHelper::a($factory->manager->name, "load_page('profile',{'uid':{$factory->manager_uid}})") : "не назначен" ?></p>
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
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <span class="title"><i class="icon-group"></i> Работники</span>
                </div>
                <div class="box-content">    
                    <table class="table table-normal">
                        <thead>
                            <tr>
                              <td style="width:40%"></td>
                              <td>Нанято</td>
                              <td>Нужно</td>
                              <td>Зарплата</td>
                            </tr>
                        </thead>
                        <tbody>
                        <? foreach ($factory->proto->workers as $tWorker) { ?>
                            <tr>
                                <td><?=$tWorker->popClass->name ?></td>
                                <td style="text-align: center;"><?=$factory->getWorkersCountByClass($tWorker->pop_class_id)?></td>
                                <td style="text-align: center;"><?=$factory->getNeedWorkersCountByClass($tWorker->pop_class_id)?></td>
                                <td style="text-align: center;"><?=$factory->getSalaryByClass($tWorker->pop_class_id)?> <?=MyHtmlHelper::icon('money')?></td>
                            </tr>
                        <? } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <span class="title"><i class="icon-home"></i> Склады предприятия</span>
                </div>
                <div class="box-content">
                    <table class="table table-normal">
                        <thead>
                            <tr>
                              <td style="width:40%"></td>
                              <td>На складе</td>
                              <td>Размер склада</td>
                            </tr>
                        </thead>
                        <tbody>
                        <? foreach ($factory->content as $store): if ($store->proto->isStorable()): ?>
                            <tr>
                                <td><?= MyHtmlHelper::icon($store->proto->class_name) ?> <?= $store->proto->name ?></td>
                                <td><?= number_format($store->count, 0, '', ' ') ?> <?= MyHtmlHelper::icon($store->proto->class_name) ?></td>
                                <td><?= number_format($factory->storageSize($store->proto_id), 0, '', ' ') ?> <?= MyHtmlHelper::icon($store->proto->class_name) ?></td>
                            </tr>
                        <? endif;
                        endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <h3>Управление</h3>
            <p>
                <button class="btn btn-sm btn-lightblue" onclick="$('#salaries_manager').modal()">Зарплаты</button>
                <button class="btn btn-sm btn-green" onclick="$('#resurses_selling_first').modal()">Продажа ресурсов</button>
                <button class="btn btn-sm btn-red" onclick="if (confirm('Вы действительно хотите уволить всех рабочих и остановить работу?')) {
                json_request('manager-stop-work')
            }">Остановить работу</button>
            </p>
        </div>
    </div>
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