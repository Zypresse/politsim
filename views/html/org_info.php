<?php
use app\components\MyHtmlHelper;
use app\components\widgets\BillListWidget;
use app\models\Bill;
?>
<h1><?=htmlspecialchars($org->name)?></h1>
<p><? if ($org->isLegislature()) { ?>Законодательная власть<? } elseif ($org->isExecutive()) { ?>Исполнительная власть<? } else { ?>Организация<? } ?> государства &laquo;<a href="#" onclick="load_page('state-info',{'id':<?=$org->state_id?>});"><?=htmlspecialchars($org->state->name)?></a>&raquo;</p>
<? if ($org->group_id) { ?><p><a class="btn" href="//vk.com/club<?=$org->group_id?>" target="_blank">
<i class="icon-home"></i> Группа в вк</a>
</p><? } ?>
<p><? switch ($org->dest) { 
  case 'dest_by_leader':
?>
Члены организации назначаются лидером этой организации.
<? break;
  case 'nation_party_vote': ?>
Члены организации избираются народным голосованием по партийным спискам.
<? break;
  case 'nation_one_party_vote': ?>
Члены организации избираются народным голосованием из кандидатов от правящей партии.
<? break;
  default: ?>
Способ формирования организации неизвестен.
<? break;
  } ?></p>
<? if ($org->isElected()) { ?><p>Члены организации переизбираются раз в <?=MyHtmlHelper::formateNumberword($org->elect_period,'дней','день','дня')?></p><? } ?>
<p><? switch ($org->leader_dest) { 
  case 'org_vote':
?>
Лидер организации избирается голосованием членов этой организации.
<? break;
  case 'nation_party_vote': ?>
Лидер организации избирается народным голосованием по партийным спискам.
<? break;
  case 'nation_individual_vote': ?>
Лидер организации избирается народным голосованием.
<? break;
  case 'other_org_vote': ?>
Лидер организации избирается голосованием членов другой организации.
<? break;
  case 'unlimited': ?>
Лидер организации занимает этот пост пожизненно.
<? break;
  default: ?>
Способ назначения лидера организации неизвестен.
<? break;
  } ?></p>
<? if ($org->can_vote_for_bills) { ?>
<p>Члены организации могут предлагать законопроекты</p>
<? } ?>
<? if ($org->can_create_bills) { ?>
<p>Члены организации могут создавать законопроекты</p>
<? } ?>
<? if ($org->isLeaderElected()) { ?><p>Лидер организации переизбирается раз в <?=MyHtmlHelper::formateNumberword($org->elect_period,'дней','день','дня')?></p><? } ?>
<h3><? if ($org->leader && $org->leader->name) { ?><?=htmlspecialchars($org->leader->name)?><? } else { ?>Лидер организации<? } ?></h3>
<? if ($org->leader && $org->leader->user) { ?><p><a href="#" onclick="load_page('profile',{'uid':<?=$org->leader->user->id?>})"><img src="<?=$org->leader->user->photo?>" alt="" style="width:32px;height:32px;"></a>
<a href="#" onclick="load_page('profile',{'uid':<?=$org->leader->user->id?>})"><?=htmlspecialchars($org->leader->user->name)?></a>
(<? if ($org->leader->user->party_id) { ?><a href="#" onclick="load_page('party-info',{'id':<?=$org->leader->user->party_id?>});"><?=htmlspecialchars($org->leader->user->party->name)?></a><? } else {  if ($org->leader->user->sex === 1) { ?>Беспартийная<? } else { ?>Беспартийный<? } ?><? } ?>)
<span class="star"><?=$org->leader->user->star?> <?=MyHtmlHelper::icon('star')?></span>
		<span class="heart"><?=$org->leader->user->heart?> <?=MyHtmlHelper::icon('heart')?></span>
		<span class="chart_pie"><?=$org->leader->user->chart_pie?> <?=MyHtmlHelper::icon('chart_pie')?></span>
		 </p>
<? } else { ?><p>Лидер организации не назначен</p><? } ?>

<? if ($org->can_vote_for_bills || $org->can_create_bills || $org->leader_can_vote_for_bills || $org->leader_can_create_bills) { ?>
<h3>Законопроекты на голосовании</h3>
<p>Список последних законопроектов <input type="button" class="btn" id="bills_show" value="Показать"></p>
<?= BillListWidget::widget(['id'=>'bills_list', 'style'=>'display:none', 'showVoteButtons'=>false, 'bills'=>Bill::find()->where(['accepted'=>0,'state_id'=>$org->state_id])->all()]) ?>
<script type="text/javascript">
 $('#bills_show').toggle(function() {
    	$(this).val('Скрыть');
    	$('#bills_list').slideDown();
    },function() {
    	$(this).val('Показать');
    	$('#bills_list').slideUp();
    })
 </script>
<? } ?>

<h3>Члены организации</h3>
<p>В организации <?=$org->getUsersCount()?> из <?=$org->getPostsCount()?> участников<br>

<div class="row">
<div class="span10 offset1" style="text-align:center">

<? foreach ($org->posts as $player) { ?>
<? if ($player->user) { ?>
<a href="#" onclick="$('.org_member').popover('destroy');load_page('profile',{'uid':<?=$player->user->id?>})" rel="popover" class="org_member" data-content="<img src='<?=$player->user->photo?>' class='img-polaroid popover_avatar' alt='' ><p><strong><?=htmlspecialchars($player->user->name)?></strong> <? if ($player->user->party_id) { ?>(<?=htmlspecialchars($player->user->party->short_name)?>)<? } ?></p><p style='margin-top:10px'><?=$player->user->star?><img src='/img/star.png' alt='' > <?=$player->user->heart?><img src='/img/heart.png' alt=''> <?=$player->user->chart_pie?><img src='/img/chart_pie.png' alt='' ><?=$player->partyReserve ? "<br><span style='font-size:70%'>Пост зарезервирован партией «{$player->partyReserve->short_name}»</span>":""?></p>" data-original-title="<?=htmlspecialchars($player->name)?>" >
<img style="background-color:<?=$player->partyReserve ? MyHtmlHelper::getPartyColor($player->partyReserve->ideologyInfo->d,true) : '#eee'?>" src="<?=$player->user->photo?>" class="img-polaroid" alt="<?=htmlspecialchars($player->user->name)?>">
</a>
<? } else { ?>
<a href="#" rel="popover" class="org_member" data-content="<p>Не назначен<?=$player->partyReserve ? "<br><span style='font-size:70%'>Пост зарезервирован партией «{$player->partyReserve->short_name}»</span>":""?></p>" data-original-title="<?=htmlspecialchars($player->name)?>" >
    <img style="background-color:<?=$player->partyReserve ? MyHtmlHelper::getPartyColor($player->partyReserve->ideologyInfo->d,true) : '#eee'?>" src="/img/chair.png" class="img-polaroid" alt="<?=htmlspecialchars($player->name)?>">
</a>
<? } ?>
<? } ?>
</div></div>
<script type="text/javascript">
  $(function(){
    $('.org_member').popover({'placement':'top'});
  })
</script>