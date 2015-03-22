<?php
use app\components\MyHtmlHelper;
?>
<h1><?=htmlspecialchars($region->name)?></h1>
<? if ($region->city) { ?><p>Столица: <?=htmlspecialchars($region->city)?></p><? } ?>
<? if ($region->state) { ?>
<p><? if ($region->isCapital()) { ?>Столица государства<? } else { ?>Принадлежит государству<? } ?> &laquo;<a href='#' onclick="$('.modal-backdrop').hide(); load_page('state-info',{'id':<?=$region->state_id?>});" ><?=htmlspecialchars($region->state->name)?></a>&raquo;</p>
<p><? if ($user->region_id == $region->id) { ?>Вы живёте здесь.<? } ?></p>
<!--<div class="btn-toolbar">
	<div class="btn-group">
  <button class="btn btn-small dropdown-toggle btn-warning" data-toggle="dropdown">
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
<div class="btn-toolbar">
  <button class="btn" <? if ($user->state_id) { ?> disabled="disabled" title="Вы не можете создать государство, имея гражданство другого государства" <? } ?> id="create_state_btn" >
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
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>   
  </div>
</div>
  <script type="text/javascript">
		$('#create_state_btn').click(function(){
    $.ajax(
      {
        url: '/api/modal/create-state-dialog?code=<?=$region->code?>',
        beforeSend:function() {
            $('#create_state_dialog_body').empty();
        },
        success:function(d) {
            if (typeof(d) == 'object' && d.error) {
                show_custom_error(d.error);
            } else {
                $('#create_state_dialog_body').html(d);
                $('#create_state_dialog').modal();
            }
        },
        error:show_error
      });
		});
  </script>
</div>
<? } ?>
 <? if ($user->region_id !== $region->id) { ?>
<div class="btn-group">
  <button class="btn btn-small" onclick="json_request('move-to',{'id':'<?=$region->id?>'},true); load_page('profile')">
    Переехать сюда
  </button>
</div><? } ?>
<p>Население: <?=MyHtmlHelper::formateNumberword($region->population,'человек','человек','человека')?></p>
<!--<p>Уровень сепаратизма: <?=$region->separate_risk?></p>-->
<!--<p>Уровень запасов нефти: <?=$region->oil?></p>-->