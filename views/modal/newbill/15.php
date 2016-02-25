<h3>Выдвижение претензий на наследование</h3>
<form class="form-horizontal">
	<div class="control-group">	
	 	<label class="control-label" for="core_id" >Исторический прототип</label>
		<div class="controls">                  
                    <select class="bill_field" id="core_id" name="core_id">
                    <?php 
                        foreach ($user->state->coreCountryStates as $c2s): 
                    ?>
                        <option value="<?=$c2s->core->id?>"><?=$c2s->core->name?> (<?=number_format($c2s->percents*100,0)?>% контролируется)</option>
                    <?php endforeach ?>
                    </select>
	 	</div>
 	</div>
</form>