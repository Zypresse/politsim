<?php
use app\components\MyHtmlHelper;

/* @var $region app\models\Region */
?>
<ul class="nav nav-tabs">
  <li><a href="#" onclick="show_region(<?=$region->id?>)">Инфо</a></li>
  <li><a href="#" onclick="show_region_population()">Население</a></li>
  <li class="active"><a href="#">Ресурсы</a></li>
</ul>
<h1><?=htmlspecialchars($region->name)?></h1>
<? /* if ($region->state_id) { ?><p><? if ($region->isCapital()) { ?>Столица государства<? } else { ?>Принадлежит государству<? } ?> &laquo;<a href='#' onclick="$('.modal-backdrop').hide(); load_page('state-info',{'id':<?=$region->state_id?>});" ><?=htmlspecialchars($region->state->name)?></a>&raquo;</p><? } */ ?>
<h3>Эффективность добычи ресурсов в этом регионе</h3>
<ul class="res-list" >
    <? foreach ($region->diggingEffs as $de): ?>
        <li <? 
        	switch ($de->group_id) {
        		case 1:
        			echo 'style="background-color:#A89B71"';
        			break;
        		case 2:
        			echo 'style="background-color:#BAB7B0"';
        			break;
        		case 3:
        			echo 'style="background-color:#8EC776"';
        			break;
        		case 4:
        			echo 'style="background-color:#EDE0AE"';
        			break;
        	}
        ?> ><?=$de->resourceProto->icon?> <?=$de->resourceProto->name?> — <?=MyHtmlHelper::zeroOne2Stars($de->k)?></li>
    <? endforeach ?>
</ul>

<script>
    

function show_region_population() {
    load_modal('region-population',{'code':'<?=$region->code?>'},'region_info','region_info_body');
    return false;
}

</script>