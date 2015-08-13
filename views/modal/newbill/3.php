<?
    use app\components\MyHtmlHelper;
?>
<h3>Переименование региона</h3>
<form class="form-horizontal">
	<div class="control-group">	
		<label class="control-label" for="bill_region_code" >Регион</label>
		<div class="controls">
			<select class="bill_field" id="bill_region_code" name="region_code">
		 	<? foreach ($user->state->regions as $region): ?>
		 		<option value="<?=$region->code?>"><?=$region->name?></option>
		 	<? endforeach ?>
			</select>
	 	</div>
	 	<label class="control-label" for="bill_new_name" >Новое название</label>
		<div class="controls">
		 	<input type="text" class="bill_field" id="bill_new_name" name="new_name" >
	 	</div>
 	</div>
</form>