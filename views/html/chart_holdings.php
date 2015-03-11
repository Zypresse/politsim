<?php

/* 
 * Copyleft license
 * I dont care how you use it
 */

use app\components\MyHtmlHelper;
?>
<h1>Рейтинг компаний</h1>
<table id="chart_states" class="table table-striped">
  <tr><th style="min-width: 250px;">Название</th><th>Капитализация</th></tr>
  <? foreach ($holdings as $holding) { ?>
<tr>
<td><a href="#" onclick="load_page('holding-info',{'id':<?=$holding->id?>})"><?=htmlspecialchars($holding->name)?></a></td>
<td><?=MyHtmlHelper::aboutNumber($holding->capital)?> <?=MyHtmlHelper::icon('money')?></td>
</tr>
<? } ?>
</table>
<script>
$(function(){
	$('#chart_states').tablePagination({'rowsPerPage':10})
})
</script>