<?
    use app\components\MyHtmlHelper;
?>
<h3>Перенос столицы</h3>
<form class="form-horizontal">
	<div class="control-group">	
		<label class="control-label" for="bill_new_capital" >Новая столица</label>
		<div class="controls">
			<select class="bill_field" id="bill_new_capital" name="new_capital">
		 	<? foreach ($user->state->regions as $region): ?>
		 		<option <? if ($region->code === $user->state->capital): ?>selected="selected"<? endif ?> value="<?=$region->code?>"><?=$region->city?></option>
		 	<? endforeach ?>
			</select>
	 	</div>
 	</div>
</form>