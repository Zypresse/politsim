<?php

use app\components\MyHtmlHelper;

usort($results, function($a, $b) {
    return $b['rating'] - $a['rating'];
});

?>
<div class="row"><div class="span4">
	<p>Явка на данный момент составила <?=round($yavka*100,2)?>%</p>
	<p>На выборы пришло <?=MyHtmlHelper::formateNumberword($yavka*$org->state->population,'человек','человек','человека')?></p>
</div></div>
<div class="row">
<div class="span2">

<span class="pie-colours-1"><? foreach ($results as $i => $result) { echo ($i ? ',' : '') . round(100*$result["rating"]/$sum_a_r,2); } ?></span>

</div>
<div class="span3">

<table class="table">
<? 
	foreach ($results as $i => $result) { 
	$elect_request = $requests[$result['id']];
?>
<tr>
	<td style="background-color:<?=MyHtmlHelper::getSomeColor($i,true)?>; width:20px;"> &nbsp; </td>
	<td style="font-size:80%">
	<? if ($leader) { ?>
		<?=htmlspecialchars($elect_request->user->name)?> (<?=htmlspecialchars($elect_request->party->short_name)?>)
	<? } else { ?>
		<?=htmlspecialchars($elect_request->party->name)?>
	<? } ?>
	</td>
	<td style="font-size:80%">
		<?=round(100*$result['rating']/$sum_a_r,2)?>%
	</td>
</tr>
<? } ?>
</table>

</div>
</div>

<script>
$(function(){

	$(".pie-colours-1").peity("pie", {
	  fill: [<? foreach ($results as $i => $result) { echo ($i ? ',' : '') . '"' . MyHtmlHelper::getSomeColor($i,true) . '"'; } ?>],
	  width: 150,
	  height:150
	})

})
</script>