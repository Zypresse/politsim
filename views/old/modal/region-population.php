<?php
use app\components\MyHtmlHelper;
?>
<ul class="nav nav-tabs">
  <li><a href="#" onclick="show_region(<?=$region->id?>)">Инфо</a></li>
  <li class="active"><a href="#">Население</a></li>
  <li><a href="#" onclick="show_region_resources()">Ресурсы</a></li>
</ul>
<h1><?=htmlspecialchars($region->name)?></h1>
<h2>Состав населения</h2>
<h4>По классам:</h4>
<table class="table">
    <tr>
        <th>Класс</th>
        <th title="Идеологии">☭</th>
        <th title="Пол">♀</th>
        <th title="Национальности">⚐</th>
        <th title="Религия">☪</th>
        <th title="Возраст">❤</th>
        <th>Число</th>
    </tr>
<?php foreach ($people_by_class as $group) { ?>
<tr>
<td><?=$group['name']?></td>
<td>
<a href="javascript:return false;" rel="popover" class="diagramm" data-content="<table class='table'><?php foreach ($group['ideologies'] as $ideology) { ?><tr><td style='background-color:<?=$ideology['color']?>; width:20px;'> &nbsp; </td><td><?=$ideology['name']?></td><td><?=$ideology['percents']?>%</td></tr><?php } ?></table>" data-original-title="Идеологии" ><span class="pie-colours-1"><?php $i = 0; foreach ($group['ideologies'] as $ideology) { ?><?=$i ? ',' : ''?><?=$ideology['percents']?><?php $i++; } ?></span></a>
</td>
<td>
<a href="javascript:return false;" rel="popover" class="diagramm" data-content="<table class='table'><?php foreach ($group['sex'] as $sex) { ?><tr><td style='background-color:<?=$sex['color']?>; width:20px;'> &nbsp; </td><td><?=$sex['name']?></td><td><?=$sex['percents']?>%</td></tr><?php } ?></table>" data-original-title="Пол" ><span class="pie-colours-2"><?php $i = 0; foreach ($group['sex'] as $sex) { ?><?=$i ? ',' : ''?><?=$sex['percents']?><?php $i++; } ?></span></a>
</td>
<td>
<a href="javascript:return false;" rel="popover" class="diagramm" data-content="<table class='table'><?php foreach ($group['nations'] as $nation) { ?><tr><td style='background-color:<?=$nation['color']?>; width:20px;'> &nbsp; </td><td><?=$nation['name']?></td><td><?=$nation['percents']?>%</td></tr><?php } ?></table>" data-original-title="Национальность" ><span class="pie-colours-3"><?php $i = 0; foreach ($group['nations'] as $nation) { ?><?=$i ? ',' : ''?><?=$nation['percents']?><?php $i++; } ?></span></a>
</td>
<td>
<a href="javascript:return false;" rel="popover" class="diagramm" data-content="<table class='table'><?php foreach ($group['religions'] as $religion) { ?><tr><td style='background-color:<?=$religion['color']?>; width:20px;'> &nbsp; </td><td><?=$religion['name']?></td><td><?=$religion['percents']?>%</td></tr><?php } ?></table>" data-original-title="Религия" ><span class="pie-colours-5"><?php $i = 0; foreach ($group['religions'] as $religion) { ?><?=$i ? ',' : ''?><?=$religion['percents']?><?php $i++; } ?></span></a>
</td>
<td>
<a href="javascript:return false;" rel="popover" class="diagramm" data-content="<table class='table'><?php foreach ($group['age'] as $age) { ?><tr><td style='background-color:<?=$age['color']?>; width:20px;'> &nbsp; </td><td><?=$age['name']?></td><td><?=$age['percents']?>%</td></tr><?php } ?></table>" data-original-title="Возраст" ><span class="pie-colours-4"><?php $i = 0; foreach ($group['age'] as $age) { ?><?=$i ? ',' : ''?><?=$age['percents']?><?php $i++; } ?></span></a>
</td>
<td><?=MyHtmlHelper::formateNumberword($group['count'],'h')?></td>
</tr>
<?php } ?>
</table>
<h4>Все группы: <button class="btn btn-xs btn-default" id="show_all_groups_population">Показать</button></h4>
<table class="table" id="all_groups_population" style="display: none">
<tr>
    <th>Класс</th>
    <th></th>
    <th title="Пол">♀</th>
    <th title="Национальности">⚐</th>
    <th title="Идеологии">☭</th>
    <th title="Религия">☪</th>
    <th title="Возраст">❤</th>
    <!--<th></th>-->
    <th>Число</th>
</tr>
<?php foreach ($people as $group) { ?>
<tr style="font-size: 70%">
    <td><?=$group->id?></td>
    <td><?=$group->classinfo->name?></td>
    <td><?php if ($group->sex) { ?>м<?php } else { ?>ж<?php } ?></td>
    <td><?=$group->nationinfo->name?></td>
    <td><?=$group->ideologyinfo->name?></td>
    <td><?=$group->religioninfo->name?></td>
    <td><?=$group->age?></td>
    <!--<td><?=$group->factory_id?></td>-->
    <td><?=MyHtmlHelper::formateNumberword($group->count,'человек','человек','человека')?></td>
</tr>
<?php } ?>
</table>


<script>
$(function(){

    $('#show_all_groups_population').click(function() {
        if ($(this).text() === 'Показать') {
            $(this).text('Скрыть');
            $('#all_groups_population').slideDown();
        } else {
            $(this).text('Показать');
            $('#all_groups_population').slideUp();
        }
    })

	$(".pie-colours-1").peity("pie", {
	  fill: [<?php $j = 0; foreach ($people_by_class as $group) { ?><?=$j ? ',' : ''?><?php $i = 0; foreach ($group['ideologies'] as $ideology) { ?><?=$i ? ',' : ''?>"<?=$ideology['color']?>"<?php $i++; } ?><?php $j++; } ?>],
	  width: 20,
	  height:20
	})
	$(".pie-colours-2").peity("pie", {
	  fill: [<?php $j = 0; foreach ($people_by_class as $group) { ?><?=$j ? ',' : ''?><?php $i = 0; foreach ($group['sex'] as $sex) { ?><?=$i ? ',' : ''?>"<?=$sex['color']?>"<?php $i++; } ?><?php $j++; } ?>],
	  width: 20,
	  height:20
	})
	$(".pie-colours-3").peity("pie", {
	  fill: [<?php $j = 0; foreach ($people_by_class as $group) { ?><?=$j ? ',' : ''?><?php $i = 0; foreach ($group['nations'] as $nation) { ?><?=$i ? ',' : ''?>"<?=$nation['color']?>"<?php $i++; } ?><?php $j++; } ?>],
	  width: 20,
	  height:20
	})
	$(".pie-colours-4").peity("pie", {
	  fill: [<?php $j = 0; foreach ($people_by_class as $group) { ?><?=$j ? ',' : ''?><?php $i = 0; foreach ($group['age'] as $age) { ?><?=$i ? ',' : ''?>"<?=$age['color']?>"<?php $i++; } ?><?php $j++; } ?>],
	  width: 20,
	  height:20
	})
	$(".pie-colours-5").peity("pie", {
	  fill: [<?php $j = 0; foreach ($people_by_class as $group) { ?><?=$j ? ',' : ''?><?php $i = 0; foreach ($group['religions'] as $religion) { ?><?=$i ? ',' : ''?>"<?=$religion['color']?>"<?php $i++; } ?><?php $j++; } ?>],
	  width: 20,
	  height:20
	})

    $('.diagramm').popover({'placement':'top','trigger':'focus'});

})


function show_region_resources() {
    load_modal('region-resources',{'code':'<?=$region->code?>'},'region_info','region_info_body');
    return false;
}

</script>