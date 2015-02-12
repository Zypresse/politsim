<?php
use app\components\MyHtmlHelper;
?>
<h1>Рейтинг партий</h1>
<? if ($state) { ?>
<h3>Партии государства &laquo;<a href="#" onclick="load_page('state_info',{'id':<?=$state->id?>})"><?=htmlspecialchars($state->name)?></a>&raquo;</h3>
<? } ?>
<table id="chart_parties" class="table table-striped">
  <tr><th style="width:60px;">Логотип</th><th style="width: 200px;">Название</th><th>Страна</th><th>Число участников</th><th>Характеристики</th></tr>
<? foreach ($parties as $party) { ?>
<tr>
<td>
<img src="<?=$party->image?>" alt="<?=$party->name?>" style="width:50px">
</td>
<td>
<a href="#" onclick="load_page('party_info',{'id':<?=$party->id?>})"><?=htmlspecialchars($party->name)?></a>
</td>
<td>
<a href="#" onclick="load_page('state_info',{'id':<?=$party->state_id?>})"><?=htmlspecialchars($party->state->short_name)?></a>
</td>
<td><?=$party->getMembersCount()?></td>
<td>
		<span class="star"><?=$party->star?> <?=MyHtmlHelper::icon('star')?></span>
		<span class="heart"><?=$party->heart?> <?=MyHtmlHelper::icon('heart')?></span>
		<span class="chart_pie"><?=$party->chart_pie?> <?=MyHtmlHelper::icon('chart_pie')?></span>
</td>
</tr>
<? } ?>
</table>
<script>
$(function(){
	$('#chart_parties').tablePagination({'rowsPerPage':10})
})
</script>