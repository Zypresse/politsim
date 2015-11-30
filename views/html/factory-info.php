<?php

use app\components\MyHtmlHelper;
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3><?= $factory->proto->name ?> &laquo;<?= htmlspecialchars($factory->name) ?>&raquo;</h3>

            <p><strong>Местоположение:</strong> <?= MyHtmlHelper::a($factory->region->name, "show_region({$factory->region_id})") ?></p>
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
                                    <td><?= $tWorker->popClass->name ?></td>
                                    <td style="text-align: center;"><?= $factory->getWorkersCountByClass($tWorker->pop_class_id) ?></td>
                                    <td style="text-align: center;"><?= $factory->getNeedWorkersCountByClass($tWorker->pop_class_id) ?></td>
                                    <td style="text-align: center;"><?= $factory->getSalaryByClass($tWorker->pop_class_id) ?> <?= MyHtmlHelper::icon('money') ?></td>
                                </tr>
                            <? } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>