<?
    use app\components\MyHtmlHelper;
?>
<h3>Провести перевыборы</h3>
<form class="form-horizontal">
	<div class="control-group">	
		<label class="control-label" for="elected_variant" >Организация</label>
		<div class="controls">
			<select class="bill_field" id="elected_variant" name="elected_variant">
				<? if ($user->state->executiveOrg->isElected() || $user->state->executiveOrg->isLeaderElected()): ?>
				<option value="<?=$user->state->executive . '_0'?>"><?=$user->state->executiveOrg->name?></option>
				<? endif ?>
				<? if ($user->state->legislatureOrg->isElected() || $user->state->legislatureOrg->isLeaderElected()): ?>
				<option value="<?=$user->state->legislature . '_0'?>"><?=$user->state->legislatureOrg->name?></option>
				<? endif ?>
			</select>
	 	</div>
 	</div>
</form>