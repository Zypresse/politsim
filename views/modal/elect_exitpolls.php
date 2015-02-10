<?php

use app\components\MyHtmlHelper;

usort($elect_requests, function($a, $b) {
    return $results[$a->id] - $results[$b->id];
});

?>
<div class="row">
<div class="span2">

<span class="pie-colours-1"><? foreach ($elect_requests as $i => $elect_request) { echo ($i ? ',' : '') . round(100*$results[$elect_request->id]/$sum_a_r,2); } ?></span>

</div>
<div class="span3">

<table class="table">
<? foreach ($elect_requests as $i => $elect_request) { ?>
<tr>
	<td style="background-color:<?=MyHtmlHelper::getSomeColor($i,true)?>; width:20px;"> &nbsp; </td>
	<td style="font-size:80%">
	<? if ($leader) { ?>
		<?=htmlspecialchars($elect_request->user->name)?> (<?=htmlspecialchars($elect_request->party->short_name)?>)
	<? } else { ?>
		<?=htmlspecialchars($elect_request->party->short_name)?>
	<? } ?>
	</td>
	<td style="font-size:80%">
		<?=round(100*$results[$elect_request->id]/$sum_a_r,2)?>%
	</td>
</tr>
<? } ?>
</table>

</div>
</div>

<script>
$(function(){

	$(".pie-colours-1").peity("pie", {
	  fill: [<? foreach ($elect_requests as $i => $elect_request) { echo ($i ? ',' : '') . '"' . MyHtmlHelper::getSomeColor($i,true) . '"'; } ?>],
	  width: 150,
	  height:150
	})

})
</script>