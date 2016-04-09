<h3>Перенос столицы</h3>
<form class="form-horizontal">
	<div class="control-group">	
		<label class="control-label" for="bill_new_capital" >Новая столица</label>
		<div class="controls">
			<select class="bill_field" id="bill_new_capital" name="new_capital">
		 	<?php foreach ($user->state->regions as $region): ?>
		 		<option <?php if ($region->id === $user->state->capital): ?>selected="selected"<?php endif ?> value="<?=$region->code?>"><?=$region->city?></option>
		 	<?php endforeach ?>
			</select>
	 	</div>
 	</div>
</form>