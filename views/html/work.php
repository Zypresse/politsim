<?php
use app\components\MyHtmlHelper;
use app\models\BillType;
use app\models\Region;
use app\models\GovermentFieldType;
use app\models\Org;

$gft = null;
?>
<div style="display:none;width: 800px;margin-left: -400px;" class="modal" id="naznach" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabelNaznach">Назначение на должность</h3>
  </div>
  <div id="naznach_body" class="modal-body">
    <p>Загрузка…</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    <!--<button class="btn btn-primary">Save changes</button>-->
  </div>
</div>
<h1>Личный кабинет</h1>
<p>Вы занимаете должность &laquo;<?=htmlspecialchars($user->post->name)?>&raquo; в организации &laquo;<a href="#" onclick="load_page('org-info',{'id':<?=$user->post->org_id?>});"><?=htmlspecialchars($user->post->org->name)?></a>&raquo;
<? if ($user->post_id === $user->post->org->leader_post) { ?><p>Вы — лидер организации &laquo;<a href="#" onclick="load_page('org-info',{'id':<?=$user->post->org_id?>});"><?=htmlspecialchars($user->post->org->name)?></a>&raquo;<? if ($user->post->org->leader_can_create_posts) { ?> и можете создавать новые должности в ней<? } ?>.</p>
<h3>Подчинённые</h3>
<p>
<strong>Список членов организации:</strong> <input type="button" class="btn" id="posts_show" value="Показать"></p>
<table id="posts_list" class="table" >
<? foreach ($user->post->org->posts as $player) { ?>
<tr><td><strong><?=htmlspecialchars($player->name)?></strong> <? if ($player->can_delete) { ?>
<button class="btn btn-danger" onclick="delete_post(<?=$player->id?>)" style="float:right;">Удалить</button>
<script type="text/javascript">
	function delete_post(id) {
		if (confirm('Вы действительно хотите удалить эту должность?')) {
			json_request('delete-post',{'id':id});
		}
	}
</script>
<? } ?></td><td>
<? if ($player->user) { ?>
<a href="#" onclick="load_page('profile',{'uid':<?=$player->user->id?>})"><img src="<?=$player->user->photo?>" alt="" style="width:32px;height:32px;"></a>
<a href="#" onclick="load_page('profile',{'uid':<?=$player->user->id?>})"><?=htmlspecialchars($player->user->name)?></a>
(<? if ($player->user->party) { ?><a href="#" onclick="load_page('party-info',{'id':<?=$player->user->party_id?>});"><?=htmlspecialchars($player->user->party->short_name)?></a><? } else { ?><? if ($player->user->sex === 1) { ?>Беспартийная<? } else { ?>Беспартийный<? } ?><? } ?>)
<span class="star"><?=$player->user->star?> <?=MyHtmlHelper::icon('star')?></span>
		<span class="heart"><?=$player->user->heart?> <?=MyHtmlHelper::icon('heart')?></span>
		<span class="chart_pie"><?=$player->user->chart_pie?> <?=MyHtmlHelper::icon('chart_pie')?></span>
		
<? if ($user->post->org->dest === 'dest_by_leader' && $player->id !== $user->post->org->leader_post) { ?>
<button class="btn btn-warning" onclick="drop_from_post(<?=$player->id?>)">Сместить с поста</button>
<script type="text/javascript">
	function drop_from_post(id) {
		if (confirm('Вы действительно хотите сместить этого человека с поста?')) {
			json_request('drop-from-post',{'id':id});
		}
	}
</script>
<? } ?>
<? } else { ?>
<? if ($user->post->org->dest === 'dest_by_leader') { ?>
<button class="btn btn-success" onclick="naznach(<?=$player->id?>)">Назначить</button>
<? } else { ?>
Не назначен
<? } ?>
<? } ?></td></tr>
<? } ?>
</table>
<script type="text/javascript">
 $('#posts_show').toggle(function() {
    	$(this).val('Скрыть');
    	$('#posts_list').slideDown();
    },function() {
    	$(this).val('Показать');
    	$('#posts_list').slideUp();
    })
 </script>
<? } ?>

<? if (($user->post_id === $user->post->org->leader_post) && $user->post->can_make_dicktator_bills) { ?>
<h3>Последние законопроекты</h3>
<p>Список последних законопроектов <input type="button" class="btn" id="bills_show" value="Показать"></p>
<dl id="bills_list" style="display:none" >
<? foreach ($bills as $bill) { ?>
	<dt><?=htmlspecialchars($bill->type->name)?> <br><ul>
		<? foreach (json_decode($bill->data,true) as $key => $value) {
			foreach ($bill->type->fields as $field) {
				if ($field->system_name === $key) {
					$name = $field->name;
					break;
				}
			}
		 ?>
		<li style="font-size:80%">
			<?=htmlspecialchars($name)?> — 
				<?
					switch ($key) {
						case 'new_capital':
							$region = Region::findByCode($value);
							$value = $region->city;
						break;
						case 'new_flag':
							$value = "<img src='{$value}' alt='New flag' style='width:50px'>";
						break;
						case 'new_color':
							$value = "<span style=\"background-color:{$val}\"> &nbsp; </span>";
						break;
						case 'goverment_field_type':
							$gft = GovermentFieldType::findByPk($value);							
							$value = $gft->name;
						break;
						case 'elected_variant':
							$value = explode('_', $value);
							$org = Org::findByPk($value[0]);
							$value = ($value[1]) ? "Выборы на пост «{$org->leader->name}» в организации «{$org->name}»" : "Выборы членов организации «{$org->name}»";
						break;
						case 'legislature_type':
							$value = (intval($value) === 1) ? 'Стандартный парламент (10 мест)' : 'Неизвестно';
						break;
						case 'goverment_field_value':
						// var_dump($gft);
						if ($gft)
							switch ($gft->type) {
								case 'checkbox':
									$value = $value ? 'Да' : 'Нет';
								break;
								case 'org_dest_leader':
								case 'org_dest_members':
									$value = [
										'nation_individual_vote'=>'голосование населения за кандидатов',
										'nation_party_vote'=>'голосование населения за партии',
										'other_org_vote'=>'голосование членов другой организации',
										'org_vote'=>'голосование членов этой же организации',
										'unlimited'=>'пожизненно',
										'dest_by_leader'=>'назначаются лидером',
										'nation_one_party_vote'=>'голосование населения за членов единственной партии',
									][$value];
								break;
							}
						break;
						default:
							$value = htmlspecialchars($value);
						break;
					}
				?>
				&laquo;<span class="dynamic_field" data-type="<?=$key?>"><?=$value?></span>&raquo;</li>
		<? } ?>
	</ul></dt>
	<dd><?

	 if ($bill->creatorpost && $bill->creatorpost->user) { ?>Предложил<? if ($bill->creatorpost->user->sex === 1) { ?>а<? } ?> <a href="#" onclick="load_page('profile',{'id':<?=$bill->creatorpost->user->id ?>})"><?=htmlspecialchars($bill->creatorpost->user->name) ?></a><? } ?> <span class="formatDate" data-unixtime="<?=$bill->created?>"><?=date("d-M-Y H:i",$bill->created) ?></span><br>
	<? if ($bill->accepted) { ?>
		Вступил в силу <span class="formatDate" data-unixtime="<?=$bill->accepted?>"><?=date("d-M-Y H:i",$bill->accepted) ?></span>
	<? } else { ?>
		Голосование продлится до <span class="formatDate" data-unixtime="<?=$bill->vote_ended?>"><?=date("d-M-Y H:i",$bill->vote_ended) ?></span>
	<? } ?>
	</dd>
<? } ?>
</dl>
<script type="text/javascript">
 $('#bills_show').toggle(function() {
    	$(this).val('Скрыть');
    	$('#bills_list').slideDown();
    },function() {
    	$(this).val('Показать');
    	$('#bills_list').slideUp();
    })
 </script>
<? } ?>

<h2>Действия</h2>

<div class="btn-toolbar">
<? if ($user->post->org->leader_post === $user->post_id) { ?>
<div class="btn-group">
  <button class="btn btn-small dropdown-toggle btn-main" data-toggle="dropdown">
    Управление организацией <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <!--<li class="divider"></li>-->
    <? if ($user->post->org->leader_can_create_posts) { ?><li><a href="#" onclick="create_new_post(<?=$user->post->org_id?>)" >Создать новую должность</a></li><? } ?>
    <li><a href="#" onclick="rename_org(<?=$user->post->org_id?>)" >Переименовать организацию</a></li>
  </ul>
</div>
<script type="text/javascript">
	function rename_org(id) {
		name = prompt('Введите новое название для организации');
		if (name != "null" && name) {
			json_request('rename-org',{'id':id,'name':name});
		}
	}

	function create_new_post(id) {
		name = prompt('Введите название новой должности');
		if (name != "null" && name) {
			json_request('create-post',{'id':id,'name':name});
		}
	}

	function naznach(id) {
		 $.ajax(
			{
				url: '/api/modal/naznach?id='+id,
				beforeSend:function() {
			  		$('#naznach_body').empty();
				},
				success:function(d) {
					if (typeof(d) == 'object' && d.result == 'error')
						show_custom_error(d.error);
					else {
				  		$('#naznach_body').html(d);
				  		$('#naznach').modal();
				  	}
				},
				error:show_error
			});
	}

	function set_post(uid,id,name,post_name) {
		if (confirm('Вы действительно хотите назначить человека по имени '+name+' на должность «'+post_name+'»?')) {
			json_request('set-post',{'id':id,'uid':uid});
			$('.modal-backdrop').remove();
		}
	}
</script>
<? } ?>

<? if (($user->post_id === $user->post->org->leader_post) && $user->post->can_make_dicktator_bills) { ?>

<div class="btn-group">
  <button class="btn btn-small btn-primary" onclick="new_zakon_modal()" >
    Новый закон
  </button>
</div>
<div style="display:none;" class="modal" id="new_zakon_select_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel123">Новый законопроект</h3>
  </div>
  <div id="new_zakon_select_modal_body" class="modal-body">
    <select id="new_zakon_select">
    	<? foreach (BillType::find()->where(['only_auto'=>0])->all() as $bill_type) { ?>
    		<option value="<?=$bill_type->id?>" ><?=htmlspecialchars($bill_type->name)?></option>
	    <? } ?>
    </select>
  </div>
  <div class="modal-footer">
  	<button class="btn btn-primary" onclick="new_zakon_form_modal()">Выбрать</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>
<div style="display:none;" class="modal" id="new_zakon_form_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1234" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel1234">Новый законопроект</h3>
  </div>
  <div id="new_zakon_form_modal_body" class="modal-body">
    Загрузка...
  </div>
  <div class="modal-footer">
  	<button class="btn btn-primary" id="send_new_zakon">Отправить</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>
<script>
function new_zakon_modal() {
	$('#new_zakon_select_modal').modal();
}

function new_zakon_form_modal() {
	bill_id = $('#new_zakon_select').val();
	$.ajax(
	{
		url: '/api/modal/new-bill?id='+bill_id,
		beforeSend:function() {
	  		$('#new_zakon_form_modal_body').empty();
		},
		success:function(d) {
			if (typeof(d) == 'object' && d.result == 'error')
				show_custom_error(d.error);
			else {
	  			$('#new_zakon_form_modal_body').html(d);
	  			$('#new_zakon_form_modal').modal();
	  		}
		},
		error:show_error
	});
}
var bill_id;
$(function(){
	$('#send_new_zakon').click(function(){
		var fields = $('.bill_field');
		var f = {'bill_type_id':bill_id};
		for (var i=0,l=fields.length;i<l;i++) {
			var $f = $(fields[i]);
			f[$f.attr("name")] = $f.val();
			// console.log($f.val(),$f.attr("type"));
			if($f.attr("type") == "checkbox") {
				f[$f.attr("name")] = $f.prop("checked")?1:0;
			}
		}
		json_request('new-bill',f);
		$('#new_zakon_form_modal').modal('close');
		return false;

	})
})
</script>
<? }  ?>

<div class="btn-group">
  <button class="btn btn-small btn-danger" onclick="self_drop_from_post()" >
    Уволиться
  </button>
</div>
<script type="text/javascript">
	function self_drop_from_post() {
		json_request('self-drop-from-post');
	}	
</script>
</div>
