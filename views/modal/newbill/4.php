<?
    use app\components\MyHtmlHelper;
?>
<h3>Переименование города</h3>
<form class="form-horizontal">
	<div class="control-group">	
		<label class="control-label" for="bill_region_code" >Город</label>
		<div class="controls">
			<select class="bill_field" id="bill_region_code" name="region_code">
		 	<? foreach ($user->state->regions as $region): ?>
		 		<option value="<?=$region->code?>"><?=$region->city?></option>
		 	<? endforeach ?>
			</select>
	 	</div>
	 	<label class="control-label" for="bill_new_city_name" >Новое название</label>
		<div class="controls">
		 	<input type="text" class="bill_field" id="bill_new_city_name" name="new_city_name" >
	 	</div>
 	</div>
</form>