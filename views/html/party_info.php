<?php
  use app\components\MyHtmlHelper;
?>
<div class="span2"><img src="<?=$party->image?>" alt="<?=$party->name?>" class="img-polaroid" style="max-width:100%"></div>
<div class="span10">
<h1><?=htmlspecialchars($party->name)?> <small>(<?=htmlspecialchars($party->short_name)?>)</small></h1>
<h3>Партия государства &laquo;<a href="#" onclick="load_page('state-info',{'id':<?=$party->state_id?>})"><?=htmlspecialchars($party->state->name)?></a>&raquo;</h3>
<? if ($party->group_id) { ?><p><a class="btn" href="//vk.com/club<?=$party->group_id?>" target="_blank">
<i class="icon-home"></i> Группа в вк</a>
</p><? } ?>
<p><strong>Идеология</strong>: <?=htmlspecialchars($party->ideologyInfo->name)?></p>
<p><strong>Лидер партии</strong>:
<? if ($party->leaderInfo) { ?>
<a href="#" onclick="load_page('profile',{'uid':<?=$party->leader?>})"><img src="<?=htmlspecialchars($party->leaderInfo->photo)?>" alt="" style="width:32px;height:32px;"></a>
<a href="#" onclick="load_page('profile',{'uid':<?=$party->leader?>})"><?=htmlspecialchars($party->leaderInfo->name)?></a>

    <span class="star"><?=$party->leaderInfo->star?> <?=MyHtmlHelper::icon('star')?></span>
    <span class="heart"><?=$party->leaderInfo->heart?> <?=MyHtmlHelper::icon('heart')?></span>
    <span class="chart_pie"><?=$party->leaderInfo->chart_pie?> <?=MyHtmlHelper::icon('chart_pie')?></span>
<? } else { ?>
не назначен
<? } ?>
</p>
</div>
<div class="span12">
<strong>Список членов партии:</strong> <input type="button" class="btn" id="members_show" value="Показать">
<ul id="members_list" >
<? foreach ($party->members as $player) { ?>
<li>
<a href="#" onclick="load_page('profile',{'uid':<?=$player->id?>})"><img src="<?=htmlspecialchars($player->photo)?>" alt="" style="width:32px;height:32px;"></a>
<a href="#" onclick="load_page('profile',{'uid':<?=$player->id?>})"><?=htmlspecialchars($player->name)?></a>

    <span class="star"><?=$player->star?> <?=MyHtmlHelper::icon('star')?></span>
		<span class="heart"><?=$player->heart?> <?=MyHtmlHelper::icon('heart')?></span>
		<span class="chart_pie"><?=$player->chart_pie?> <?=MyHtmlHelper::icon('chart_pie')?></span>
</li>
<? } ?>
</ul>
<script type="text/javascript">
 $('#members_show').toggle(function() {
    	$(this).val('Скрыть');
    	$('#members_list').slideDown();
    },function() {
    	$(this).val('Показать');
    	$('#members_list').slideUp();
    })
 </script>

<h3>Действия</h3>
<div class="btn-toolbar">
<? if ($user->isPartyLeader() && $user->party_id === $party->id) {
    $emptyPosts = [];
    foreach ($user->state->legislatureOrg->posts as $post) {
        if ($post->party_reserve === $user->party_id && is_null($post->user)) {
            $emptyPosts[] = $post;
        }
    }
    if (sizeof($emptyPosts)) echo "<p style='color:red'>Есть свободные посты в правительстве: ". MyHtmlHelper::formateNumberword (sizeof($emptyPosts), "зарезервированных должностей", "зарезервированная должность", "зарезервированные должности")  . "</p>";
    ?>
<div class="btn-group">
  <button class="btn btn-small dropdown-toggle btn-info" data-toggle="dropdown">
    Управление <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href="#" onclick="rename_party(<?=$party->id?>)" >Переименовать партию</a></li>
    <li><a href="#" onclick="change_party_logo(<?=$party->id?>)" >Сменить эмблему партии</a></li>
    <? if (sizeof($emptyPosts)) { ?><li><a href="#" onclick="$('#party-reserve-post-set').modal()">Назначить на зарезервированный пост</a></li><? } ?>
  </ul>
</div>
    <? if (sizeof($emptyPosts)) { ?>
<div style="display:none" class="modal" id="party-reserve-post-set" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Создание партии</h3>
  </div>
  
  <div id="party-reserve-post-set_body" class="modal-body">
     <form class="well form-horizontal">
      <div class="control-group">
	    <label class="control-label" for="#post_id">Пост</label>
	    <div class="controls">
                <select id="post_id">
                <? foreach ($emptyPosts as $post) { ?>
                    <option value="<?=$post->id?>"><?=$post->name?></option>
                <? } ?>
                </select>
	    </div>
	  </div>
      <div class="control-group">
	    <label class="control-label" for="#user_id">Человек</label>
	    <div class="controls">
                <select id="user_id">
                <? foreach ($party->members as $user) { if (!$user->post_id) {?>
                    <option value="<?=$user->id?>"><?=$user->name?></option>
                <? }} ?>
                </select>
	    </div>
	  </div>   
     </form>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="party_reserve_post_set()">Назначить</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>
<? } ?>
<script type="text/javascript">
  
  function rename_party(id) {
    var name = prompt('Введите новое название для партии');
    var short_name = prompt('Введите новое короткое название для партии');
    if (name != "null" && name && short_name) {
      json_request('rename-party',{'id':id,'name':name,'short_name':short_name});
    }
  }
  function change_party_logo(id) {
    var image = prompt('Введите ссылку на новый логотип для партии');
    if (image != "null" && image) {
      json_request('change-party-logo',{'id':id,'image':image});
    }
  }
  function party_reserve_post_set() {
      json_request('party-reserve-set-post',{'post_id':$('#post_id').val(),'uid':$('#user_id').val()});
  }

</script>
<? } ?>

<? if ($party->id === $user->party_id) { ?>

<div class="btn-group">
  <button class="btn btn-small dropdown-toggle btn-warning" onclick="if (confirm('Вы действительно хотите выйти из партии?')) { json_request('leave-party',{});  }">
    Выйти из партии
  </button>
</div>
	
<? } elseif (!$user->party_id && $user->state_id === $party->state_id) { ?>

<div class="btn-group">
  <button class="btn btn-small dropdown-info btn-info" onclick="json_request('join-party',{'party_id':<?=$party->id?>})">
    Вступить в партию
  </button>
</div>
	
</div>
<? } ?>
</div>