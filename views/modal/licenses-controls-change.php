<label class="control-label" for="is_only_goverment" >Гос. монополия</label>
<div class="controls">
	<input type="checkbox" name="is_only_goverment" id="is_only_goverment" value="1" <?=$stateLicense->is_only_goverment?"checked='checked'":""?> >
</div>

<label class="control-label" for="is_need_confirm" >Обязательное подтвержение заявки министром</label>
<div class="controls">
	<input type="checkbox" name="is_need_confirm" id="is_need_confirm" value="1" <?=$stateLicense->is_need_confirm?"checked='checked'":""?> >
</div>