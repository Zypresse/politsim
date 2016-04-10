<h3>Переименовать организацию</h3>
<form class="form-horizontal">
	<div class="control-group">	
		<label class="control-label" for="org_id" >Организация</label>
		<div class="controls">
			<select class="bill_field" id="org_id" name="org_id">
				<?php if ($user->state->executiveOrg): ?><option value="<?=$user->state->executive?>"><?=$user->state->executiveOrg->name?></option><?php endif ?>
				<?php if ($user->state->legislatureOrg): ?><option value="<?=$user->state->legislature?>"><?=$user->state->legislatureOrg->name?></option><?php endif ?>
			</select>
	 	</div>
        </div>
    	<div class="control-group">	
                <label class="control-label" for="bill_new_name" >Новое название</label>
		<div class="controls">
		 	<input type="text" class="bill_field" id="bill_new_name" name="new_name" >
	 	</div>
 	</div>
</form>