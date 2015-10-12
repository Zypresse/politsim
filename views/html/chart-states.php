<?php
use app\components\MyHtmlHelper;
?>
<h1>Рейтинг стран</h1>
<table id="chart_states" class="table table-striped">
  <tr><th style="width:60px">Флаг</th><th style="min-width: 250px;">Название</th><th>Население</th></tr>
  <? foreach ($states as $state) { ?>
<tr>
<td><img src="<?=$state->flag?>" alt="<?=$state->flag?>" style="width:50px;"></td>
<td><a href="#" onclick="load_page('state-info',{'id':<?=$state->id?>})"><?=htmlspecialchars($state->name)?></a></td>
<td><?=MyHtmlHelper::formateNumberword($state->population,'h')?></td>
</tr>
<? } ?>
</table>
<script>
$(function(){
	$('#chart_states').tablePagination({'rowsPerPage':10})
})
</script>