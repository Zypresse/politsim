<?php

use app\components\MyHtmlHelper;

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
<h3><?=$factory->proto->name?> &laquo;<?=htmlspecialchars($factory->name)?>&raquo;</h3>

<p><strong>Местоположение:</strong> <?=MyHtmlHelper::a($factory->region->name, "show_region({$factory->region_id})")?></p>
<p><strong>Владелец:</strong> <?=MyHtmlHelper::a($factory->holding->name,"load_page('holding-info',{'id':{$factory->holding_id}})")?></p>
<p><strong>Управляющий:</strong> <?=$factory->manager ? MyHtmlHelper::a($factory->manager->name,"load_page('profile',{'uid':{$factory->manager_uid}})") : "не назначен"?></p>
<p>
    <strong>Статус:</strong> <?=$factory->statusName?> 
    <? if ($factory->status < 0) { ?>
    (запланированная дата окончания строительства: 
        <span class="formatDate" data-unixtime="<?=$factory->builded?>">
            <?=date('d-M-Y H:i',$factory->builded)?>
        </span>)
    <? } ?>
</p>
<p><strong>Необходимые работники:</strong>
<ul>
    <? foreach ($factory->proto->workers as $tWorker) { ?>
    <li>
        <?=$tWorker->popClass->name?> — <?=$tWorker->count*$factory->size?>
    </li>
    <? } ?>
</ul>
</p>
<p><strong>Зарплаты:</strong>
<? if (count($factory->salaries)) { ?>
<ul>
    <? foreach ($factory->salaries as $salary) { ?>
    <li>
        <?=$salary->popClass->name?> — <?=$salary->salary?> <?=MyHtmlHelper::icon('money')?>
    </li>
    <? } ?>
</ul>
<? } else { ?>
<br>Зарплаты не установлены
<? } ?>
</p>
<p><strong>Нанятые работники:</strong>
<? if (count($factory->workers)) { ?>
<ul>
    <? foreach ($factory->workers as $worker) { ?>
    <li>
        <?=$worker->classinfo->name?> <?=$worker->sex?"(женщины)":"(мужчины)"?> — <?=$worker->count?>
    </li>
    <? } ?>
</ul>
<? } else { ?>
<br>Не нанято ни одного работника
<? } ?>
</p>
        </div>
    </div>
</div>