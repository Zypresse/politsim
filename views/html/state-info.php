<?php
    
    /** @var app\models\State $state */

    use app\components\MyHtmlHelper,
        yii\helpers\Html;

    $KN = [
        'nation_individual_vote'=>'голосование населения за кандидатов',
        'nation_party_vote'=>'голосование населения за партии',
        'other_org_vote'=>'голосование членов другой организации',
        'org_vote'=>'голосование членов этой же организации',
        'unlimited'=>'пожизненно',
        'dest_by_leader'=>'назначаются лидером',
        'nation_one_party_vote'=>'голосование населения за членов единственной партии'
    ];

    $show_create_party = isset($_GET['show_create_party']);

?>
<div class="container">
    <div class="row">
<div class="col-md-2">
<img src="<?=$state->flag?>" alt="Флаг" class="img-polaroid" style="max-width:100%">
</div>
<div class="col-md-10">
<h1><?=htmlspecialchars($state->name)?> <small>(<?=htmlspecialchars($state->short_name)?>)</small></h1>
<p>
<strong>Форма гос. устройства:</strong> <?=htmlspecialchars($state->structure->name)?><br>
<strong>Столица:</strong> <a href="#" onclick="show_region('<?=$state->capital?>')"><?=htmlspecialchars($state->capitalRegion->city)?></a><br>
<strong>Население:</strong> <?=MyHtmlHelper::formateNumberword($state->population,'h')?>
<? if ($state->core) { ?>
<br><strong>Наследник государства:</strong> <?=Html::img('/img/cores/'.$state->core->id.'.png');?> <?=$state->core->name?> (контроллирует <?=number_format($state->getCoreCountryState()->percents*100,0)?>% территорий)
<? } ?>
</p></div></div>
<div class="col-md-12">
<h3>Правительство</h3>
<p><strong><? if ($state->executiveOrg && $state->executiveOrg->leader) { ?><?=htmlspecialchars($state->executiveOrg->leader->name)?><? } else { ?>Лидер государства<? } ?></strong>:
<? if ($state->executiveOrg && $state->executiveOrg->leader && $state->executiveOrg->leader->user) { ?>
<a href="#" onclick="load_page('profile',{'uid':<?=$state->executiveOrg->leader->user->id?>})"><img src="<?=$state->executiveOrg->leader->user->photo?>" alt="" style="width:32px;height:32px;"></a>
<a href="#" onclick="load_page('profile',{'uid':<?=$state->executiveOrg->leader->user->id?>})"><?=htmlspecialchars($state->executiveOrg->leader->user->name)?></a>

(<? if ($state->executiveOrg && $state->executiveOrg->leader->user->party) { ?><a href="#" onclick="load_page('party-info',{'id':<?=$state->executiveOrg->leader->user->party_id?>})"><?=htmlspecialchars($state->executiveOrg->leader->user->party->name)?></a><? } else { ?><? if ($state->executiveOrg->leader->user->sex === 1) { ?>Беспартийная<? } else { ?>Беспартийный<? } ?><? } ?>)

		<span class="star"><?=$state->executiveOrg->leader->user->star?> <?=MyHtmlHelper::icon('star')?></span>
		<span class="heart"><?=$state->executiveOrg->leader->user->heart?> <?=MyHtmlHelper::icon('heart')?></span>
		<span class="chart_pie"><?=$state->executiveOrg->leader->user->chart_pie?> <?=MyHtmlHelper::icon('chart_pie')?></span>
<? } else { ?>
не назначен
<? } ?>
</p>
<h4><? if ($state->executiveOrg) { ?><a href="#" onclick="load_page('org-info',{'id':<?=$state->executive?>});"><?=htmlspecialchars($state->executiveOrg->name)?></a><? } else { ?>Не сформирована<? } ?> <small>Исполнительная власть</small></h4>
<? if ($state->executiveOrg && $state->executiveOrg->isElected()) { ?>Следующие выборы — <span class="formatDate" data-unixtime="<?=$state->executiveOrg->next_elect?>"><?=date("d-M-Y H:i",$state->executiveOrg->next_elect)?></span><br><? } ?>
<? if ($state->executiveOrg && $state->executiveOrg->isLeaderElected()) { ?>Следующие выборы лидера организации — <span class="formatDate" data-unixtime="<?=$state->executiveOrg->next_elect?>"><?=date("d-M-Y H:i",$state->executiveOrg->next_elect)?></span><br><? } ?>
<h4><? if ($state->legislatureOrg) { ?><a href="#" onclick="load_page('org-info',{'id':<?=$state->legislature?>});"><?=htmlspecialchars($state->legislatureOrg->name)?></a><? } else { ?>Не сформирована<? } ?> <small>Законодательная власть</small></h4>
<? if ($state->legislatureOrg && $state->legislatureOrg->isElected()) { ?>Следующие выборы — <span class="formatDate" data-unixtime="<?=$state->legislatureOrg->next_elect?>"><?=date("d-M-Y H:i",$state->legislatureOrg->next_elect)?></span><br><? } ?>
<? if ($state->legislatureOrg && $state->legislatureOrg->isLeaderElected()) { ?>Следующие выборы лидера организации — <span class="formatDate" data-unixtime="<?=$state->legislatureOrg->next_elect?>"><?=date("d-M-Y H:i",$state->legislatureOrg->next_elect)?></span><br><? } ?>

<h3>Конституция</h3>
<ul>
<? foreach ($state->articles as $article) { ?>

<li><strong><?=$article->proto->name?></strong> — 
<? if ($article->proto->type === 'checkbox') { ?>
  <?=($article->value)?'ДА':'НЕТ'?>
<? } elseif ($article->proto->type === 'org_dest_members' || $article->proto->type === 'org_dest_leader' ) { ?>
  <?=$KN[$article->value]?>
<? } else { ?>
  <?=$article->value?>
<? } ?></li>

<? } ?>
</ul>

<h3>Экономика</h3>
<ul>
    <?    foreach ($state->licenses as $license) { ?>
    <li><strong><?=$license->proto->name?>:</strong><br>
        <? if ($license->is_only_goverment) { ?>
        Гос. монополия
        <? } else { ?>
        Стоимость лицензии: <?=MyHtmlHelper::moneyFormat($license->cost)?><br>
        Стоимость лицензии для нерезидентов: <?=MyHtmlHelper::moneyFormat($license->cost_noncitizens)?>
        <? if ($license->is_need_confirm) { ?>
        <br> Требуется подтверждение министра
        <? } ?>
        <? if ($license->is_need_confirm_noncitizens) { ?>
        <br> Требуется подтверждение министра для нерезидентов
        <? } ?>
        <? } ?>
    </li>
    <? } ?>
</ul>

<h3>Территория</h3>
<strong>Список регионов:</strong> <input type="button" class="btn btn-sm btn-default" id="regions_show" value="Показать">
<ul id="region_list" >
<? foreach ($state->regions as $region) { ?>
<li><a href="#" onclick="show_region('<?=$region->code?>')"><?=htmlspecialchars($region->name)?> (<?=MyHtmlHelper::formateNumberword($region->population,'h')?>)</a></li>
<? } ?>
</ul>
<div style="display:none" class="modal" id="region_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Информация о регионе</h3>
  </div>
  <div id="region_info_body" class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    <!--<button class="btn btn-primary">Save changes</button>-->
  </div>
</div>

<h3>Действия</h3>

<div class="btn-toolbar">
<div class="btn-group">
  <button class="btn btn-sm dropdown-toggle btn-lightblue" data-toggle="dropdown">
    Гражданство <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <!--<li class="divider"></li>-->
    <? if ($user->state_id === $state->id) { ?>
      <li><a href="#" onclick="json_request('drop-citizenship')" >Отказаться от гражданства</a></li>
    <? } else { ?>
      <li><a href="#" onclick="<? if ($user->state_id === 0) { ?>json_request('get-citizenship',{'state_id':<?=$state->id?>})<? } else { ?>show_custom_error('Вы уже имеете гражданство другого государства. Во время альфа теста иметь двойное гражданство запрещено.')<? } ?>" >Получить гражданство</a></li>
    <? } ?>
    <!--<li><a href='#' >Запросить политическое убежище</a></li>-->
  </ul>
</div>

	<div class="btn-group">
  <button class="btn btn-sm dropdown-toggle btn-primary" data-toggle="dropdown">
    Политика <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <!--<li class="divider"></li>-->
    <? if ($user->state_id === $state->id) { ?>
      <? if ($user->party_id === 0 && $state->allow_register_parties) { ?><li><a href='#' onclick="$('#create_party').modal();" >Создать партию</a></li><? } ?>
    <? } ?>
    <li><a href='#' onclick="load_page('chart-parties',{'state_id':<?=$state->id?>});" >Список партий</a></li>
    <li><a href='#' onclick="load_page('elections',{'state_id':<?=$state->id?>});" >Выборы</a></li>
  </ul>
</div>
</div><? if ($state->allow_register_parties || $show_create_party) { ?>
<div style="display:none" class="modal" id="create_party" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <? if (!$show_create_party) { ?><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><? } ?>
    <h3 id="myModalLabel">Создание партии</h3>
  </div>
  
  <div id="create_party_body" class="modal-body">
    <form class="well form-horizontal">
      <div class="control-group">
	    <label class="control-label" for="#party_name">Название</label>
	    <div class="controls">
	      <input type="text" id="party_name" placeholder="Единая Россия">
	    </div>
	  </div>
    <div class="control-group">
      <label class="control-label" for="#party_name_short">Короткое название</label>
      <div class="controls">
        <input type="text" id="party_name_short" placeholder="ЕР">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="#party_ideology">Идеология</label>
      <div class="controls">
        <select id="party_ideology" >
        <? foreach ($ideologies as $ideology) { ?>
          <option value="<?=$ideology->id?>"><?=htmlspecialchars($ideology->name)?></option>
        <? } ?>
        </select>
      </div>
    </div>
	  <div class="control-group">
	    <label class="control-label" for="#party_image">Ссылка на логотип<br><small>Используйте сервисы загрузки изображений, например <a href="https://imgur.com" target="_new">Imgur</a></small></label>
	    <div class="controls">
	  		<input type="text" id="party_image" placeholder="https://i.imgur.com/TNBKSPO.gif">
	  	</div>
	  </div>
	  <!--<span class="help-block">Example block-level help text here.</span>
	  <label class="checkbox">
	    <input type="checkbox"> Check me out
	  </label>
	  <button type="submit" class="btn btn-green">Submit</button>-->
	</form>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="create_party()">Создать</button>
    <? if (!$show_create_party) { ?><button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button><? } ?>
  </div>
</div>
<script type="text/javascript">
	function create_party() {

		name = $('#party_name').val();
    name_short = $('#party_name_short').val();
    image = $('#party_image').val();
		ideology = $('#party_ideology').val();
		//$('.modal-backdrop').hide(); 
		json_request('create-party',{'name':name,'short_name':name_short,'image':image,'ideology':ideology,'firsth_of_state':firsth_of_state},false);
    load_page('party-info',{},500);
		return true;
	}
</script><? } ?>
<script>
var firsth_of_state = false;
$(function(){

   <? if ($show_create_party) { ?>
   firsth_of_state = true;
    $('#create_party').modal({'keyboard':false,'backdrop':'static'});
    <? } ?>


    $('#regions_show').click(function() {
        if ($(this).val() === 'Показать') {
            $(this).val('Скрыть');
            $('#region_list').slideDown();
        } else {
            $(this).val('Показать');
            $('#region_list').slideUp();
        }
    })
})
  
</script>
</div>
</div>
