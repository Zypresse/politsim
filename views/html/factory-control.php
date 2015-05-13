<?php

use app\components\MyHtmlHelper;

?>
<h2><?=$factory->type->name?> &laquo;<?=htmlspecialchars($factory->name)?>&raquo;</h2>

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
<br>Не нанято ни одного работника
<? } ?>
</p>
<p><strong>Нанятые работники:</strong>
<? if (count($factory->workers)) { ?>
<ul>
    <? foreach ($factory->workers as $worker) { ?>
    <li>
        <?=$worker->population->classinfo->name?> — <?=$worker->population->count?>
    </li>
    <? } ?>
</ul>
<? } else { ?>
<br>Не нанято ни одного работника
<? } ?>
</p>
<p><strong>Открытые вакансии:</strong>
<? if (count($factory->vacansies)) { ?>
<ul>
    <? foreach ($factory->vacansies as $vacansy) { ?>
    <li>
        <?=$vacansy->popClass->name?> — <?=$vacansy->count_need?>, зарплата <?=$vacansy->salary?> <?=MyHtmlHelper::icon('money')?>
    </li>
    <? } ?>
</ul>
<? } else { ?>
<br>Не открыто ни одной вакансии
<? } ?>
</p>

<h3>Действия</h3>
<p>
    <button class="btn btn-small btn-info" onclick="$('#salaries_manager').modal()">Зарплаты</button>
</p>

<div style="display:none" class="modal" id="salaries_manager" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123213" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel123213">Управление зарплатами</h3>
  </div>
  <div id="salaries_manager_body" class="modal-body">
        <h3>Вакансии:</h3>
        <dl>
            <? foreach ($factory->type->workers as $tWorker) { 
                $actived = 0;
                foreach ($factory->workers as $worker) {
                    if ($worker->population->class == $tWorker->pop_class_id) {
                        $actived += $worker->population->count;
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
            <dt><?=$tWorker->popClass->name?> <?=$actived?>/<?=$tWorker->count*$factory->size?></dt>
            <dd>Зарплата: <input id="salary_<?=$tWorker->pop_class_id?>" type="number" value="<?=$salary_value?>" style="width:50px" > <?=MyHtmlHelper::icon('money')?> в месяц</dd>
            <? } ?>
        </dl>
  </div>
  <div class="modal-footer">
      <button class="btn btn-primary" onclick="save_salaries()">Сохранить</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>

<div style="display:none" class="modal" id="region_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel345543" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel345543">Информация о регионе</h3>
  </div>
  <div id="region_info_body" class="modal-body">
    <p>Загрузка…</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    <!--<button class="btn btn-primary">Save changes</button>-->
  </div>
</div>
<script>

function save_salaries() {
    var data = {
        'factory_id': <?=$factory->id?>
    }
    <? foreach ($factory->type->workers as $tWorker) { ?>
            data.salary_<?=$tWorker->pop_class_id?> = $('#salary_<?=$tWorker->pop_class_id?>').val();
    <? } ?>
        json_request('factory-manager-salaries-save',data);
}

function show_region(region) {
    $.ajax(
        {
          url: '/api/modal/region-info?id='+region,
          beforeSend:function() {
              $('#region_info_body').empty();
          },
          success:function(d) {
              $('#region_info_body').html(d);
              $('#region_info').modal();
          },
          error:show_error
        });
    return false;
}

</script>