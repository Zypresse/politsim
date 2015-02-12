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
	Состоит в партии <a href="#" onclick="load_page('party_info',{'id':<?=$user->party_id?>})"><?=htmlspecialchars($user->party->name)?></a>
<? } else { ?>
	<? if ($user->sex === 1) { ?>Беспартийная<? } else { ?>Беспартийный<? } ?>
<? } ?></p>
<p><? if ($user->state_id) { ?>
	<? if ($user->sex === 1) { ?>Гражданка<? } else { ?>Гражданин<? } ?> государства <a href="#" onclick="load_page('state_info',{'id':<?=$user->state_id?>})"><?=htmlspecialchars($user->state->name)?></a>
<? } else { ?>
	<? if ($user->sex === 1) { ?>Гражданка<? } else { ?>Гражданин<? } ?> мира
<? } ?></p>
<? if ($user->region) { ?><p>Живет в регионе «<?=htmlspecialchars($user->region->name)?>»</p><? } ?>
<? if ($user->post) { ?><p><i class="icon-briefcase"></i> Занимает пост &laquo;<?=htmlspecialchars($user->post->name)?>&raquo; в организации &laquo;<a href="#" onclick="load_page('org_info',{'id':<?=$user->post->org_id?>});"><?=htmlspecialchars($user->post->org->name)?></a>&raquo;</p><? } ?>
<? if (sizeof($user->medales)) { ?><p>
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
    Передать деньги <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href='#' onclick="transfer_money('open')" >Передать деньги открыто</a></li>
    <!--<li class="divider"></li>-->
    <li><a href='#' onclick="transfer_money('hidden')" >Передать деньги тайно</a></li>
    <li><a href="#" onclick="transfer_money('anonym')" >Передать деньги анонимно</a></li>
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
 </div><div class="btn-group">
  <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">
    Подробная информация <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href='#' onclick="load_page('capital',{'uid':<?=$user->id?>})" >Капитал</a></li>
  </ul>
 </div>
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
      <span id="money_transfer_type_open" class="help-block money_transfer_help-block">О передаче денег узнает любой, кто захочет узнать</span>
      <span id="money_transfer_type_hidden" class="help-block money_transfer_help-block">О передаче денег узнают разве что спецслужбы</span>
      <span id="money_transfer_type_anonym" class="help-block money_transfer_help-block">Получатель не узнает, кто передал деньги</span>
      <input type="hidden" id="money_transfer_type" value="open">
    </form>
  </div>
  <div class="modal-footer">
    <button onclick="json_request('transfer_money',{'type':$('#money_transfer_type').val(),'count':$('#money_transfer_count').val(),'uid':<?=$user->id?>})" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Передать</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>
<script type="text/javascript">
  
  function transfer_money(type) {
    $('.money_transfer_help-block').hide();
    $('#money_transfer_type_'+type).show();
    $('#money_transfer_type').val(type);
    $('#transfer_money_dialog').modal();
  }

  function public_statement(type) {
    json_request('public_statement',{'uid':<?=$user->id?>,'type':type});
  }

</script>
<? } ?>
</div>