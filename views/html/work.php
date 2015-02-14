<?php
use app\components\MyHtmlHelper;
use app\models\BillType;
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
<p>Вы занимаете должность &laquo;<?=htmlspecialchars($user->post->name)?>&raquo; в организации &laquo;<a href="#" onclick="load_page('org_info',{'id':<?=$user->post->org_id?>});"><?=htmlspecialchars($user->post->org->name)?></a>&raquo;
<? if ($user->post_id === $user->post->org->leader_post) { ?><p>Вы — лидер организации &laquo;<a href="#" onclick="load_page('org_info',{'id':<?=$user->post->org_id?>});"><?=htmlspecialchars($user->post->org->name)?></a>&raquo;<? if ($user->post->org->leader_can_create_posts) { ?> и можете создавать новые должности в ней<? } ?>.</p>
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
(<? if ($player->user->party) { ?><a href="#" onclick="load_page('party-info',{'id':<?=$player->user->party_id?>});"><?=htmlspecialchars($player->user->party->name)?></a><? } else { ?><? if ($player->user->sex === 1) { ?>Беспартийная<? } else { ?>Беспартийный<? } ?><? } ?>)
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
		<li style="font-size:80%"><?=htmlspecialchars($name)?> — &laquo;<span class="dynamic_field" data-type="<?=$key?>"><?=htmlspecialchars($value)?></span>&raquo;</li>
		<? } ?>
	</ul></dt>
	<dd><?

	 if ($bill->creatorpost && $bill->creatorpost->user) { ?>Предложил<? if ($bill->creatorpost->user->sex === 1) { ?>а<? } ?> <a href="#" onclick="load_page('profile',{'id':<?=$bill->creatorpost->user->id ?>})"><?=htmlspecialchars($bill->creatorpost->user->name) ?></a><? } ?> <?=date("d-M-Y H:i",$bill->created) ?><br>
	<? if ($bill->accepted) { ?>
		Вступил в силу <?=date("d-M-Y H:i",$bill->accepted) ?>
	<? } else { ?>
		Голосование продлится до <?=date("d-M-Y H:i",$bill->vote_ended) ?>
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
				url: '/nodejs?a=naznach_modal&id='+id,
				beforeSend:function() {
			  		$('#naznach_body').empty();
				},
				success:function(d) {
			  		$('#naznach_body').html(d);
			  		$('#naznach').modal();
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
		url: '/nodejs?a=new_zakon_modal&id='+bill_id,
		beforeSend:function() {
	  		$('#new_zakon_form_modal_body').empty();
		},
		success:function(d) {
	  		$('#new_zakon_form_modal_body').html(d);
	  		$('#new_zakon_form_modal').modal();
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

<script>
var goverment_field_type;

$(function(){
	//gf_cache = {};
	var fields = $('.dynamic_field').toArray();
	//console.log(fields);
	update_fields_recursive(fields);

})


function update_fields_recursive(fields) {
	//console.log(fields)
	var f = fields.shift(),
		$f = $(f),
		val= $f.text();
		console.log(val,$f.data('type'));
		if (gf_cache[$f.data('type')] && gf_cache[$f.data('type')][val]) {
			$f.html(gf_cache[$f.data('type')][val]);
			if (fields.length) update_fields_recursive(fields);
		} else {
			gf_cache[$f.data('type')] = {};
		switch($f.data('type')) {
			case 'new_capital':
				$f.text('Город в регионе с кодом '+val);
				get_json('region-info',{'code':val},function(info){
					$f.text(info.city);
					gf_cache['new_capital'][val] = info.city;
					if (fields.length) update_fields_recursive(fields);
				})
				
			break;
			case 'new_flag':
				$f.html("<img src=\""+val+"\" alt=\""+val+"\" style=\"width:50px\">");
				gf_cache['new_flag'][val] = "<img src=\""+val+"\" alt=\""+val+"\" style=\"width:50px\">";
				if (fields.length) update_fields_recursive(fields);
			break;
			case 'new_color':
				$f.html("<span style=\"background-color:"+val+"\"> &nbsp; </span>");
				gf_cache['new_color'][val] = "<span style=\"background-color:"+val+"\"> &nbsp; </span>";
				if (fields.length) update_fields_recursive(fields);
			break;
			case 'goverment_field_type':
				$f.text('Статья конституции №'+val);
				gf_cache['goverment_field_type'][val] = 'Статья конституции №'+val;
				get_json('goverment-field-type-info',{'id':val},function(info){
					$f.text(info.name);
					gf_cache['goverment_field_type'][val] = info.name;
					goverment_field_type = info.type;
					//console.log(info.city);
					if (fields.length) update_fields_recursive(fields);
				})
			break;
			case 'elected_variant':
				
				var org_id = parseInt(val.split('_')[0]),
					is_leader = parseInt(val.split('_')[1]);

				$f.text('Выборы '+ (is_leader ? 'лидера организации' : 'в организацию' ) +' №'+org_id);
				gf_cache['elected_variant'][val] = 'Выборы '+ (is_leader ? 'лидера организации' : 'в организацию' ) +' №'+org_id;

				get_json('org-info',{'id':org_id},function(info){
					$f.text('Выборы '+ (is_leader ? 'лидера организации' : 'в организацию' ) +' '+info.name+'');
					gf_cache['elected_variant'][val] = 'Выборы '+ (is_leader ? 'лидера организации' : 'в организацию' ) +' '+info.name+'';

					if (fields.length) update_fields_recursive(fields);
				})
			break;
			case "legislature_type":
				val = parseInt(val);
				var legislature_types = [
					'неизвестный',
                    'стандартный парламент (10 мест)'
				];
				$f.text(legislature_types[val]);
				gf_cache['legislature_type'][val] = legislature_types[val];
				if (fields.length) update_fields_recursive(fields);
			break;
			case 'goverment_field_value':
				switch (goverment_field_type) {
				case "checkbox":
					$f.text((parseInt(val))?'ДА':'НЕТ');
					gf_cache['goverment_field_value'][val] = (parseInt(val))?'ДА':'НЕТ';
				break;
				case "org_dest_leader":
				case "org_dest_members":
					var names = {
						'nation_individual_vote':'голосование населения за кандидатов',
						'nation_party_vote':'голосование населения за партии',
						'other_org_vote':'голосование членов другой организации',
						'org_vote':'голосование членов этой же организации',
						'unlimited':'пожизненно',
						'dest_by_leader':'назначаются лидером',
						'nation_one_party_vote':'голосование населения за членов единственной партии',
					};
					$f.text(names[val]);
					gf_cache['goverment_field_value'][val] = names[val];
				break;
				default:
					
				break;
				} 
				if (fields.length) update_fields_recursive(fields);
			break;
			default:
				if (fields.length) update_fields_recursive(fields)
			break;
		}
	}
}
</script>
