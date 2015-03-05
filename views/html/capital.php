<?php
use app\components\MyHtmlHelper;
?>
<h3>Капитал пользователя по имени <?=htmlspecialchars($user->name)?></h3>
<p>Наличные: <?=number_format($user->money,2,'.',' ')?> <?=MyHtmlHelper::icon('coins')?></p>
<h4>Акции:</h4>
<table class="table">
<? if (sizeof($user->stocks)) { foreach ($user->stocks as $stock) { ?>
    <tr>
        <td><?=$stock->holding->name?></td>
        <td><?=MyHtmlHelper::formateNumberword($stock->count, "акций","акция","акции")?> (<?=$stock->getPercents()?>%)</td>
    </tr>
<? }} else { ?>
    <tr><td colspan="2">Не владеет акциями</td></tr>
<? } ?></table>
<p><button class="btn btn-success" onclick="$('#create_holding_dialog').modal()">Создать холдинг</button></p>

<div style="display:none" class="modal" id="create_holding_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Создать холдинг</h3>
  </div><form class="well form-horizontal">
  <div id="create_holding_dialog_body" class="modal-body">
     
      <div class="control-group">
      <label class="control-label" for="#holding_name">Название</label>
      <div class="controls">
        <input type="text" id="holding_name" placeholder="ОАО «Рога и копыта»">
      </div>
      </div>
      <p>Создание холдинга отнимет у вас 10 000 <?=MyHtmlHelper::icon('coins')?></p>
  </div>
  <div class="modal-footer">
      <button type="submit" onclick="json_request('create-holding',{'name':$('#holding_name').val()})" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Создать</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div></form>
</div>

<h4>Последние совершённые сделки:</h4>
<table class="table">
<thead>
	<tr><th>Время</th><th>От кого</th><th>Кому</th><th>Деньги</th><th>Вещи</th><th>Тип</th></tr>
</thead>
<tbody>
<? foreach ($dealings as $d) { ?>

<tr><td class="prettyDate" data-unixtime="<?=$d->time?>"></td><td><? if (!$d->is_anonim || $d->from_uid == $viewer_id) { ?><a href="#" onclick="load_page('profile',{'uid':<?=$d->from_uid?>});"><img src="<?=$d->sender->photo?>" alt=""> <?=htmlspecialchars($d->sender->name)?></a><? } else { ?>Неизвестный отправитель<? } ?></td><td><a href="#" onclick="load_page('profile',{'uid':<?=$d->to_uid?>});"><img src="<?=$d->recipient->photo?>" alt=""> <?=htmlspecialchars($d->recipient->name)?></a></td><td><?=number_format($d->sum,2,'.',' ')?> <?=MyHtmlHelper::icon('coins')?></td><td></td><td><? if ($d->is_secret) { ?>Тайный<? } ?> <? if ($d->is_anonim) { ?>Анонимный<? } ?> <? if (!$d->is_secret && !$d->is_anonim) { ?>Обычный<? } ?></td></tr>

<? } ?>
</tbody>
</table>