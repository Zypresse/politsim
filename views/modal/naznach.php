<p><strong>Кандидаты на должность «<?=htmlspecialchars($post->name)?>»:</strong></p><br>
<table id="chart_peoples" class="table table-striped">
  <tr><th style="width: 200px;">Имя</th><th>Партия</th><th>Характеристики</th><th>Действие</th></tr>
  <? foreach ($people as $player) { ?>
<tr>
<td>
<a href="#" onclick="load_page('profile',{'uid':<?=$player->id?>})"><img src="{{ player.photo }}" alt="" style="width:32px;height:32px;"></a>
<a href="#" onclick="load_page('profile',{'uid':<?=$player->id?>})"><?=htmlspecialchars($player->name)?></a></td>
<td><? if ($player->party_id) { ?><a href="#" onclick="load_page('party-info',{'id':<?=$player->party_id?>})"><?=htmlspecialchars($player->party->name)?></a><? } else { echo ($player->sex == 1) ? 'Беспартийная' : 'Беспартийный'; } ?></td>
<td>
		<span class="star"><?=$player->star?> <img src="/img/star.png" alt="Известность" title="Известность" style=""></span>
		<span class="heart"><?=$player->heart?> <img src="/img/heart.png" alt="Доверие" title="Доверие" style=""></span>
		<span class="chart_pie"><?=$player->chart_pie?> <img src="/img/chart_pie.png" alt="Успешность" title="Успешность" style=""></span>
</td>
<td><button class="btn btn-primary" onclick="set_post(<?=$player->id?>, <?=$post->id?>, '<?=htmlspecialchars($player->name)?>', '<?=htmlspecialchars($post->name)?>')" >Назначить</button></td>
</tr>
  <? } ?>
</table>
<script>
$(function(){
	$('#chart_peoples').tablePagination({'rowsPerPage':5})
})
</script>