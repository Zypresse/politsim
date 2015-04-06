<?php
use app\components\MyHtmlHelper;
?>
<div class="span3" style="position:relative">
	<img src="<?=$user->photo_big?>" class="img-polaroid">

	<div class="photo_bottom_container">
		<span class="star"><?=$user->star?> <?=MyHtmlHelper::icon('star')?></span>
		<span class="heart"><?=$user->heart?> <?=MyHtmlHelper::icon('heart')?></span>
		<span class="chart_pie"><?=$user->chart_pie?> <?=MyHtmlHelper::icon('chart_pie')?></span>
	</div>
</div>
<div class="span9">
<h1><?=htmlspecialchars($user->name)?> <? if ($is_own) { ?><small>(это вы)</small><? } ?></h1>
<? if ($user->uid_vk) ?><p><a class="btn" href="//vk.com/id<?=$user->uid_vk?>" target="_blank"><i class="icon-user"></i> Профиль в ВК</a></p>
<p><? if ($user->party) { ?>
	Состоит в партии <a href="#" onclick="load_page('party-info',{'id':<?=$user->party_id?>})"><?=htmlspecialchars($user->party->name)?></a>
<? } else { ?>
	<? if ($user->sex === 1) { ?>Беспартийная<? } else { ?>Беспартийный<? } ?>
<? } ?></p>
<p><? if ($user->state) { ?>
	<? if ($user->sex === 1) { ?>Гражданка<? } else { ?>Гражданин<? } ?> государства <a href="#" onclick="load_page('state-info',{'id':<?=$user->state_id?>})"><?=htmlspecialchars($user->state->name)?></a>
<? } else { ?>
	<? if ($user->sex === 1) { ?>Гражданка<? } else { ?>Гражданин<? } ?> мира
<? } ?></p>
<? if ($user->region) { ?><p>Живет в регионе «<?=htmlspecialchars($user->region->name)?>»</p><? } ?>
<? if ($user->post) { ?><p><i class="icon-briefcase"></i> Занимает пост &laquo;<?=htmlspecialchars($user->post->name)?>&raquo;<? if ($user->post->org) { ?> в организации &laquo;<a href="#" onclick="load_page('org-info',{'id':<?=$user->post->org_id?>});"><?=htmlspecialchars($user->post->org->name)?></a>&raquo;</p><? } ?><? } ?>
<? if (count($user->medales)) { ?><p>
<h4>Значки:</h4>
<? foreach ($user->medales as $medale) { ?>
<a href="#" rel="popover" class="medale" data-content="<?=htmlspecialchars($medale->medaletype->desc)?>" data-original-title="<?=htmlspecialchars($medale->medaletype->name)?>" ><img src="<?=$medale->medaletype->image?>" alt="<?=htmlspecialchars($medale->medaletype->name)?>" class="img-polaroid" ></a> 
<? } ?>
</p>
<script type="text/javascript">
  $(function(){
    $('.medale').popover({'placement':'top'});
  })
</script>
<? } ?>

<? if (!$is_own) { ?>
<div class="btn-toolbar">
	<div class="btn-group">
  <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">
    Провести сделку <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href='#' onclick="transfer_money()" >Передать деньги</a></li>
    <li><a href='#' onclick="transfer_stocks()" >Передать акции</a></li>
    <li class="divider"></li>
    <li><a href="#" onclick="sell_stocks()" >Продать акции</a></li>
  </ul>
</div><div class="btn-group">
  <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">
    Сделать публичное заявление <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href='#' onclick="public_statement('positive')" >Поддержать этого политика</a></li>
    <li><a href='#' onclick="public_statement('negative')" >Негативно высказаться</a></li>
    <li><a href="#" onclick="public_statement('affront')" >Публично оскорбить</a></li>
  </ul>
 </div><div class="btn-group">
  <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">
    Публикации <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href='#' onclick="load_page('twitter',{'uid':<?=$user->id?>})" >Микроблог</a></li>
  </ul>
 </div><!--<div class="btn-group">
  <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">
    Подробная информация <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href='#' onclick="load_page('capital',{'uid':<?=$user->id?>})" >Капитал</a></li>
  </ul>
 </div>-->
</div>
<div style="display:none" class="modal" id="transfer_money_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Передать деньги</h3>
  </div>
  <div id="transfer_money_dialog_body" class="modal-body">
     <form class="well form-horizontal">
      <div class="control-group">
      <label class="control-label" for="#money_transfer_count">Количество</label>
      <div class="controls">
        <input type="number" id="money_transfer_count" placeholder="100"> <img src="/img/coins.png" alt="золотых монет" title="золотых монет" style="">
      </div>
      </div>
      <div class="control-group">
      <label class="control-label" >Способ</label>
      <div class="controls">   
          <label><input type="checkbox" value="hidden" name="money_transfer_hidden" id="money_transfer_hidden"> Тайно</label>
          <label><input type="checkbox" value="anonym" name="money_transfer_anonym" id="money_transfer_anonym"> Анонимно</label>
      <span id="money_transfer_type_open" class="help-block money_transfer_help-block">О передаче денег узнает любой, кто захочет узнать</span>
      
      <span id="money_transfer_type_hidden" class="help-block money_transfer_help-block">О передаче денег узнают разве что спецслужбы</span>
      <span id="money_transfer_type_anonym" class="help-block money_transfer_help-block">Получатель не узнает, кто передал деньги</span>
      <input type="hidden" id="money_transfer_type" value="open">
      </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <button onclick="send_money()" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Передать</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>
<div style="display:none" class="modal" id="transfer_stocks_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Заключение сделки</h3>
  </div>
  <div id="transfer_stocks_dialog_body" class="modal-body">
     <form class="well form-horizontal">
      <div class="control-group">
      <label class="control-label" for="#holding_id">Компания</label>
      <div class="controls">
          <select id="holding_id" >
              <? foreach ($viewer->stocks as $stock) { ?>
              <option value="<?=$stock->holding_id?>"><?=$stock->holding->name?> (<?=number_format($stock->count,0,'',' ')?>)</option>
              <? } ?>
          </select>
      </div>
      </div>
      <div class="control-group" id="dealing_cost_block">
      <label class="control-label" for="#dealing_cost">Цена</label>
      <div class="controls">
          <input type="number" id="dealing_cost" placeholder="" > <?=MyHtmlHelper::icon('money')?>
      </div>
      </div>
      <div class="control-group">
      <label class="control-label" for="#dealing_stocks_count">Количество</label>
      <div class="controls">
        <input type="number" id="dealing_stocks_count" placeholder="">
      </div>
      </div>
      <div class="control-group" id="dealing_stocks_pricebyone_block">
      <label class="control-label" >Цена за акцию</label>
      <div class="controls">
          <input type="number" id="dealing_stocks_pricebyone" readonly="readonly" placeholder=""> <?=MyHtmlHelper::icon('money')?>
      </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <button onclick="create_stocks_dealing()" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Создать</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>
<script type="text/javascript">
  
  function update_stocks_count() {
    if ($('#dealing_stocks_count').val()) {
        $('#dealing_stocks_pricebyone').val($('#dealing_cost').val()/$('#dealing_stocks_count').val());
    } else {
        $('#dealing_stocks_pricebyone').val(0);
    }
  }
  
  function transfer_money() {
    $('.money_transfer_help-block').hide();
    $('#money_transfer_type_open').show();
    $('#money_transfer_hidden').change(function(){
        if ($(this).prop('checked')) {
            $('#money_transfer_type_hidden').show();
            $('#money_transfer_type_open').hide();
        } else {
            $('#money_transfer_type_hidden').hide();
            $('#money_transfer_type_open').show();
        }
    });
    $('#money_transfer_anonym').change(function(){
        if ($(this).prop('checked')) {
            $('#money_transfer_type_anonym').show();
        } else {
            $('#money_transfer_type_anonym').hide();
            if (!$('#money_transfer_hidden').prop('checked')) $('#money_transfer_type_open').show();
        }
    });
    $('#money_transfer_count').change(function(){
        if ($(this).val()<0) {
            $(this).val(0);
        } else if ($(this).val()><?=$user->money?>) {
            $(this).val(<?=$user->money?>);
        }
        
        
    });
    $('#transfer_money_dialog').modal();
  }
  
  function transfer_stocks(){
      $('#dealing_cost').val(0);
      $('#dealing_cost_block').hide();
      $('#dealing_stocks_pricebyone_block').hide();
      $('#transfer_stocks_dialog').modal();
  }
  
  
  function sell_stocks() {
      $('#dealing_cost').val('');
      $('#dealing_cost_block').show();
      $('#dealing_stocks_pricebyone_block').show();
      
      $('#dealing_cost').keyup(update_stocks_count);
      $('#dealing_cost').change(update_stocks_count);
      
      $('#dealing_stocks_count').keyup(update_stocks_count);
      $('#dealing_stocks_count').change(update_stocks_count);
      $('#transfer_stocks_dialog').modal();
  }
  
  function send_money() {
      json_request('transfer-money',{
          'count':$('#money_transfer_count').val(),
          'uid':<?=$user->id?>,
          'is_anonim':$('#money_transfer_anonym').prop('checked') ? 1 : 0,
          'is_secret':$('#money_transfer_hidden').prop('checked') ? 1 : 0
      });
  }
  
  function create_stocks_dealing() {
        json_request('stocks-dealing',{
            'holding_id':$('#holding_id').val(),
            'count':$('#dealing_stocks_count').val(),
            'cost':$('#dealing_cost').val(),
            'uid':<?=$user->id?>
        });
  }

  function public_statement(type) {
    json_request('public-statement',{'uid':<?=$user->id?>,'type':type});
  }

</script>
<? } ?>
</div>