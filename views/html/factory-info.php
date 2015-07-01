<?php

use app\components\MyHtmlHelper;

?>
<h3><?=$factory->type->name?> &laquo;<?=htmlspecialchars($factory->name)?>&raquo;</h3>

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
    <? foreach ($factory->type->workers as $tWorker) { ?>
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
        <?=$worker->classinfo->name?> — <?=$worker->count?>
    </li>
    <? } ?>
</ul>
<? } else { ?>
<br>Не нанято ни одного работника
<? } ?>
</p>

<div style="display:none" class="modal" id="region_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Информация о регионе</h3>
  </div>
  <div id="region_info_body" class="modal-body">
    <p>Загрузка…</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    <!--<button class="btn btn-primary">Save changes</button>-->
  </div>
</div>