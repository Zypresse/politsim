<?php
use app\components\MyHtmlHelper;
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
<h1>Рейтинг игроков</h1>
<p>Вы на <strong><?=$place?></strong> месте!</p>
<table id="chart_peoples" class="table table-striped">
  <tr><th style="min-width: 250px;">Имя</th><th>Страна</th><th>Партия</th><th>Характеристики</th></tr>
  <? foreach ($users as $player) { ?>
<tr>
<td>
<a href="#" onclick="load_page('profile',{'uid':<?=$player->id?>})"><img src="<?=$player->photo?>" alt="" style="width:32px;height:32px;"></a>
&nbsp;
<a href="#" onclick="load_page('profile',{'uid':<?=$player->id?>})"><?=htmlspecialchars($player->name)?></a></td>
<td><? if ($player->state) { ?><?=$player->state->getHtmlShortName()?><? } else { ?><? if ($player->sex === 1) { ?>Гражданка<? } else { ?>Гражданин<? } ?> мира<? } ?></td>
<td><? if ($player->party) { ?><?=$player->party->getHtmlName()?><? } else { ?><? if ($player->sex === 1) { ?>Беспартийная<? } else { ?>Беспартийный<? } ?><? } ?></td>
<td>
		<span class="star"><?=$player->star?> <?=MyHtmlHelper::icon('star')?></span>
		<span class="heart"><?=$player->heart?> <?=MyHtmlHelper::icon('heart')?></span>
		<span class="chart_pie"><?=$player->chart_pie?> <?=MyHtmlHelper::icon('chart_pie')?></span>
</td>
</tr>
  <? } ?>
</table>
        </div>
    </div>
</div>
<script>
$(function(){
	$('#chart_peoples').tablePagination({'rowsPerPage':10})
})
</script>