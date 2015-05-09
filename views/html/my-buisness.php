<?php

/**
 * Страница управления бизнесом
 * 
 * @var app\models\User $user 
 **/

use app\components\MyHtmlHelper;
use yii\helpers\Html;

?>
<h1>Управление бизнесом</h1>
<h3>Ваши акции: <button class="btn btn-mini" id="stock_list_button">скрыть/показать</button></h3>
<table class="table" id="stocks_list" style="display: none">
    <thead>
        <tr>
            <th>Фирма</th>
            <th>Количество акций</th>
            <th>Примерная рыночная стоимость</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
<? if (count($user->stocks)) { foreach ($user->stocks as $stock) { ?>
    <tr>
        <td><a href="#" onclick="load_page('holding-info',{'id':<?=$stock->holding_id?>})"><?=$stock->holding->name?></a></td>
        <td><?=MyHtmlHelper::formateNumberword($stock->count, "акций","акция","акции")?> (<?=round($stock->getPercents(),2)?>%)</td>
        <td>≈ <?=number_format($stock->getCost(),0,'',' ')?> <?=MyHtmlHelper::icon('money')?></td>
        <td><?=Html::a("Управление","#",['class'=>'btn btn-primary', 'onclick'=>'load_page("holding-control",{"id":'.$stock->holding_id.'})'])?></td>
    </tr>
<? }} else { ?>
    <tr><td colspan="4">Не владеет акциями</td></tr>
<? } ?>
    </tbody>
</table>

<h3>Управление: <button class="btn btn-mini" id="managefactories_list_button">скрыть/показать</button></h3>
<table id="managefactories_list" class="table" style="display: none">
    <thead>
        <tr>
            <th>Обьект</th>
            <th>Фирма</th>
            <th>Регион</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
<? if (count($user->factories)) { foreach ($user->factories as $factory) { ?>
    <tr>
        <td><?=$factory->name?></td>
        <td><a href="#" onclick="load_page('holding-info',{'id':<?=$factory->holding_id?>})"><?=$factory->holding->name?></a></td>
        <td><?=$factory->region->name?></td>
        <td><?=$factory->statusName?></td>
        <td><?=Html::a("Управление","#",['class'=>'btn btn-primary', 'onclick'=>'load_page("factory-control",{"id":'.$factory->id.'})'])?></td>
    </tr>
<? }} else { ?>
    <tr><td colspan="5">Не управляет ни одним обьектом</td></tr>
<? } ?>
    </tbody>
</table>

<? if ($user->state && $user->state->allow_register_holdings) { ?>
<p><button class="btn btn-success" onclick="$('#create_holding_dialog').modal()">Создать акционерное общество</button></p>

<div style="display:none" class="modal" id="create_holding_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Создать акционерное общество</h3>
  </div><form class="well form-horizontal">
  <div id="create_holding_dialog_body" class="modal-body">
     
      <div class="control-group">
      <label class="control-label" for="#holding_name">Название</label>
      <div class="controls">
        <input type="text" id="holding_name" placeholder="ОАО «Рога и копыта»">
      </div>
      </div>
      <p>Создание холдинга отнимет у вас 10 000 <?=MyHtmlHelper::icon('money')?></p>
      <p>Из них 5 000 <?=MyHtmlHelper::icon('money')?> попадут на расчётный счёт фирмы</p>
  </div>
  <div class="modal-footer">
    <button type="submit" onclick="json_request('create-holding',{'name':$('#holding_name').val()})" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Создать</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div></form>
</div>
<? } ?>

<script>
    $(function(){
        $('#stock_list_button').toggle(function(){
            $('#stocks_list').slideDown();
        },function(){
            $('#stocks_list').slideUp();
        })
        $('#managefactories_list_button').toggle(function(){
            $('#managefactories_list').slideDown();
        },function(){
            $('#managefactories_list').slideUp();
        })
    })
</script>