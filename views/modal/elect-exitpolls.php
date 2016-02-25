<?php

use app\components\MyHtmlHelper;

usort($results, function($a, $b) {
    switch (true) {
    	case $b['rating'] < $a['rating']:
    		return -1;
    	case $b['rating'] > $a['rating']:
    		return 1;
    	default:
    		return 0;
    }
});

?>
<div class="row"><div class="span6">
	<p>Явка на данный момент составила <?=round($yavka*100,2)?>%</p>
	<p>На выборы пришло <?=MyHtmlHelper::formateNumberword($yavka*$org->state->population,'человек','человек','человека')?></p>
</div></div>
<div class="row">
<div class="span3" style="text-align: center;">

<span class="pie-colours-1"><?php foreach ($results as $i => $result) { echo ($i ? ',' : '') . ($sum_a_r?round(100*$result["rating"]/$sum_a_r,2):($i?0:100)); } ?></span>

</div>
<div class="span3">

<table class="table">
<?php 
	foreach ($results as $i => $result) { 
	$elect_request = $requests[$result['id']];
?>
<tr>
	<td style="background-color:<?=MyHtmlHelper::getSomeColor($i,true)?>; width:20px;"> &nbsp; </td>
	<td style="font-size:80%">
	<?php if ($leader) { ?>
		<?=htmlspecialchars($elect_request->user->name)?> (<?=$elect_request->user->party ? htmlspecialchars($elect_request->user->party->short_name) : ($elect_request->user->sex == 1 ? 'беспартийная' : 'беспартийный')?>)
	<?php } else { ?>
		<?=MyHtmlHelper::a($elect_request->party->name,"load_page('party-info',{'id':{$elect_request->party->id}})")?>
	<?php } ?>
	</td>
	<td style="font-size:80%">
		<?=($sum_a_r?round(100*$result["rating"]/$sum_a_r,2):($i?0:100))?>%
	</td>
</tr>
<?php } ?>
</table>

</div>
</div>

<script>
$(function(){

	$(".pie-colours-1").peity("pie", {
	  fill: [<?php foreach ($results as $i => $result) { echo ($i ? ',' : '') . '"' . MyHtmlHelper::getSomeColor($i,true) . '"'; } ?>],
	  width: 150,
	  height:150
	})

})
</script>