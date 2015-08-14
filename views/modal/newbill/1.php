<?
    use app\components\MyHtmlHelper;
?>
<h3>Переименование страны</h3>
<form class="form-horizontal">
	<div class="control-group">	
		<label class="control-label" for="bill_new_name" >Новое название</label>
		<div class="controls">
		 	<input type="text" class="bill_field" id="bill_new_name" name="new_name" value="<?=$user->state->name?>" >
	 	</div>
        </div>
    	<div class="control-group">	
	 	<label class="control-label" for="bill_new_short_name" >Новое короткое название</label>
		<div class="controls">
		 	<input type="text" class="bill_field" id="bill_new_short_name" name="new_short_name"  value="<?=$user->state->short_name?>" >
	 	</div>
 	</div>
</form>