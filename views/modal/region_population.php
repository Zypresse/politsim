<?php
use app\components\MyHtmlHelper;
?>
<ul class="nav nav-tabs">
  <li><a href="#" onclick="show_region(<?=$region->id?>)">Инфо</a></li>
  <li class="active"><a href="#">Население</a></li>
  <li><a href="#" onclick="show_region_resurses()">Ресурсы</a></li>
</ul>
<h1><?=htmlspecialchars($region->name)?></h1>
<h2>Состав населения</h2>
<h4>По классам:</h4>
<table class="table">
    <tr>
        <th>Класс</th>
        <th title="Идеологии">☭</th>
        <th title="Пол">♀</th>
        <th title="Национальности">★</th>
        <th title="Возраст">❤</th>
        <th>Число</th>
    </tr>
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
<h4>Все группы: <button class="btn" id="show_all_groups_population">Показать</button></h4>
<table class="table" id="all_groups_population" style="display: none">
<tr>
    <th>Класс</th>
    <th></th>
    <th title="Пол">♀</th>
    <th title="Национальности">★</th>
    <th title="Идеологии">☭</th>
    <th title="Возраст">❤</th>
    <th></th>
    <th>Число</th>
</tr>
<? foreach ($people as $group) { ?>
<tr style="font-size: 70%"><td><?=$group->id?></td><td><?=$group->classinfo->name?></td><td><? if ($group->sex) { ?>м<? } else { ?>ж<? } ?></td><td><?=$group->nationinfo->name?></td><td><?=$group->ideologyinfo->name?></td><td><?=$group->age?></td><td><?=$group->factory_id?></td><td><?=MyHtmlHelper::formateNumberword($group->count,'человек','человек','человека')?></td></tr>
<? } ?>
</table>


<script>
$(function(){

$('#show_all_groups_population').toggle(function() {
    	$(this).val('Скрыть');
    	$('#all_groups_population').slideDown();
    },function() {
    	$(this).val('Показать');
    	$('#all_groups_population').slideUp();
    })

	$(".pie-colours-1").peity("pie", {
	  fill: [<? $j = 0; foreach ($people_by_class as $group) { ?><?=$j ? ',' : ''?><? $i = 0; foreach ($group['ideologies'] as $ideology) { ?><?=$i ? ',' : ''?>"<?=$ideology['color']?>"<? $i++; } ?><? $j++; } ?>],
	  width: 20,
	  height:20
	})
	$(".pie-colours-2").peity("pie", {
	  fill: [<? $j = 0; foreach ($people_by_class as $group) { ?><?=$j ? ',' : ''?><? $i = 0; foreach ($group['sex'] as $sex) { ?><?=$i ? ',' : ''?>"<?=$sex['color']?>"<? $i++; } ?><? $j++; } ?>],
	  width: 20,
	  height:20
	})
	$(".pie-colours-3").peity("pie", {
	  fill: [<? $j = 0; foreach ($people_by_class as $group) { ?><?=$j ? ',' : ''?><? $i = 0; foreach ($group['nations'] as $nation) { ?><?=$i ? ',' : ''?>"<?=$nation['color']?>"<? $i++; } ?><? $j++; } ?>],
	  width: 20,
	  height:20
	})
	$(".pie-colours-4").peity("pie", {
	  fill: [<? $j = 0; foreach ($people_by_class as $group) { ?><?=$j ? ',' : ''?><? $i = 0; foreach ($group['age'] as $age) { ?><?=$i ? ',' : ''?>"<?=$age['color']?>"<? $i++; } ?><? $j++; } ?>],
	  width: 20,
	  height:20
	})

    $('.diagramm').popover({'placement':'top','trigger':'focus'});

})


function show_region_resurses() {
    load_modal('region-resurses',{'code':'<?=$region->code?>'},'region_info','region_info_body');
    return false;
}

</script>