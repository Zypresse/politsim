<?php
use app\components\MyHtmlHelper;
?>
<ul class="nav nav-tabs">
  <li class="active">
    <a href="#">Инфо</a>
  </li>
  <li><a href="#" onclick="show_region_population()">Население</a></li>
  <li><a href="#" onclick="show_region_resurses()">Ресурсы</a></li>
</ul>
<h1><?=htmlspecialchars($region->name)?></h1>
<? if ($region->city) { ?><p>Столица: <?=htmlspecialchars($region->city)?></p><? } ?>
<? if ($region->state) { ?>
<p><? if ($region->isCapital()) { ?>Столица государства<? } else { ?>Принадлежит государству<? } ?> &laquo;<a href='#' onclick="$('.modal-backdrop').hide(); load_page('state-info',{'id':<?=$region->state_id?>});" ><?=htmlspecialchars($region->state->name)?></a>&raquo;</p>
<p><? if ($user->region_id == $region->id) { ?>Вы живёте здесь.<? } ?></p>
<!--<div class="btn-toolbar">
	<div class="btn-group">
  <button class="btn btn-sm dropdown-toggle btn-red" data-toggle="dropdown">
    Экстремизм <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href='#'>Организация митингов</a></li>
    <li><a href='#'>Терроризм</a></li>
    <li><a href="#">Вооруженное восстание</a></li>
  </ul>
</div>-->
<? } else { ?>
<p>В этом регионе царит анархия</p>
 <? if ($user->region_id === $region->id) { ?>
<div class="btn-toolbar">
  <button class="btn btn-green" <? if ($user->state_id) { ?> disabled="disabled" title="Вы не можете создать государство, имея гражданство другого государства" <? } ?> id="create_state_btn" >
    Основать государство
  </button>

  <div style="display:none" class="modal" id="create_state_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Создание государства</h3>
  </div>
  <div id="create_state_dialog_body" class="modal-body">
    Загрузка...
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" id="create_state_submit">Создать</button>
    <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>   
  </div>
</div>
  <script type="text/javascript">
    $('#create_state_btn').click(function(){
        load_modal('create-state-dialog',{'code':'<?=$region->code?>'},'create_state_dialog','create_state_dialog_body');
    });
  </script>
</div>
<? }} ?>
 <? if ($user->region_id !== $region->id) { ?>
<div class="btn-group">
  <button class="btn btn-sm btn-blue" onclick="json_request('move-to',{'id':'<?=$region->id?>'},true); setTimeout(function(){load_page('profile')},200)">
    Переехать сюда
  </button>
</div><? } ?>
<p>Население: <?=MyHtmlHelper::formateNumberword($region->population,'человек','человек','человека')?></p>


<script>

function show_region_population() {
    load_modal('region-population',{'code':'<?=$region->code?>'},'region_info','region_info_body');
    return false;
}

function show_region_resurses() {
    load_modal('region-resurses',{'code':'<?=$region->code?>'},'region_info','region_info_body');
    return false;
}

</script>