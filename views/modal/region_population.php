<?php
use app\components\MyHtmlHelper;
?>
<h1><?=htmlspecialchars($region->name)?></h1>
<h2>Состав населения</h2>
<h4>По классам:</h4>
<table class="table">
<tr><th>Класс</th><th>Идеологии</th><th>Пол</th><th>Национальности</th><th>Возраст</th><th>Число</th></tr>
<? foreach ($people_by_class as $group) { ?>
<tr>
<td><?=$group['name']?></td>
<td>
<a href="javascript:return false;" rel="popover" class="diagramm" data-content="<table class='table'><? foreach ($group['ideologies'] as $ideology) { ?><tr><td style='background-color:<?=$ideology['color']?>; width:20px;'> &nbsp; </td><td><?=$ideology['name']?></td><td><?=$ideology['percents']?>%</td></tr><? } ?></table>" data-original-title="Идеологии" ><span class="pie-colours-1"><? $i = 0; foreach ($group['ideologies'] as $ideology) { ?><?=$i ? ',' : ''?><?=$ideology['percents']?><? $i++; } ?></span></a>
</td>
<td>
<a href="javascript:return false;" rel="popover" class="diagramm" data-content="<table class='table'><? foreach ($group['sex'] as $sex) { ?><tr><td style='background-color:<?=$sex['color']?>; width:20px;'> &nbsp; </td><td><?=$sex['name']?></td><td><?=$sex['percents']?>%</td></tr><? } ?></table>" data-original-title="Пол" ><span class="pie-colours-2"><? $i = 0; foreach ($group['sex'] as $sex) { ?><?=$i ? ',' : ''?><?=$sex['percents']?><? $i++; } ?></span></a>
</td>
<td>
<a href="javascript:return false;" rel="popover" class="diagramm" data-content="<table class='table'><? foreach ($group['nations'] as $nation) { ?><tr><td style='background-color:<?=$nation['color']?>; width:20px;'> &nbsp; </td><td><?=$nation['name']?></td><td><?=$nation['percents']?>%</td></tr><? } ?></table>" data-original-title="Национальность" ><span class="pie-colours-3"><? $i = 0; foreach ($group['nations'] as $nation) { ?><?=$i ? ',' : ''?><?=$nation['percents']?><? $i++; } ?></span></a>
</td>
<td>
<a href="javascript:return false;" rel="popover" class="diagramm" data-content="<table class='table'><? foreach ($group['age'] as $age) { ?><tr><td style='background-color:<?=$age['color']?>; width:20px;'> &nbsp; </td><td><?=$age['name']?></td><td><?=$age['percents']?>%</td></tr><? } ?></table>" data-original-title="Возраст" ><span class="pie-colours-4"><? $i = 0; foreach ($group['age'] as $age) { ?><?=$i ? ',' : ''?><?=$age['percents']?><? $i++; } ?></span></a>
</td>
<td><?=MyHtmlHelper::formateNumberword($group['count'],'h')?></td>
</tr>
<? } ?>
</table>
<h4>Все группы:</h4>
<table class="table">
<tr><th>Класс</th><th>Пол</th><th>Национальность</th><th>Идеология</th><th>Число</th></tr>
<? foreach ($people as $group) { ?>
<tr><td><?=$group->classinfo->name?></td><td><? if ($group->sex) { ?>м<? } else { ?>ж<? } ?></td><td><?=$group->nationinfo->name?></td><td><?=$group->ideologyinfo->name?></td><td><?=MyHtmlHelper::formateNumberword($group->count,'человек','человек','человека')?></td></tr>
<? } ?>
</table>


<script>
$(function(){

	$(".pie-colours-1").peity("pie", {
	  fill: [<? foreach ($people_by_class as $j => $group) { ?><?=$j ? ',' : ''?><? $i = 0; foreach ($group['ideologies'] as $ideology) { ?><?=$i ? ',' : ''?>"<?=$ideology['color']?>"<? $i++; } ?><? } ?>],
	  width: 20,
	  height:20
	})
	$(".pie-colours-2").peity("pie", {
	  fill: [<? foreach ($people_by_class as $j => $group) { ?><?=$j ? ',' : ''?><? $i = 0; foreach ($group['sex'] as $sex) { ?><?=$i ? ',' : ''?>"<?=$sex['color']?>"<? $i++; } ?><? } ?>],
	  width: 20,
	  height:20
	})
	$(".pie-colours-3").peity("pie", {
	  fill: [<? foreach ($people_by_class as $j => $group) { ?><?=$j ? ',' : ''?><? $i = 0; foreach ($group['nations'] as $nation) { ?><?=$i ? ',' : ''?>"<?=$nation['color']?>"<? $i++; } ?><? } ?>],
	  width: 20,
	  height:20
	})
	$(".pie-colours-4").peity("pie", {
	  fill: [<? foreach ($people_by_class as $j => $group) { ?><?=$j ? ',' : ''?><? $i = 0; foreach ($group['age'] as $age) { ?><?=$i ? ',' : ''?>"<?=$age['color']?>"<? $i++; } ?><? } ?>],
	  width: 20,
	  height:20
	})

    $('.diagramm').popover({'placement':'top','trigger':'focus'});

})
</script>