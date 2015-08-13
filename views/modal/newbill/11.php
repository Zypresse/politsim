<?
    use app\components\MyHtmlHelper,
    	app\models\HoldingLicenseType;
?>
<h3>Смена порядка выдачи лицензий</h3>
<form class="form-horizontal">
	<div class="control-group">	
		<label class="control-label" for="license_id" >Тип лицензии</label>
		<div class="controls">
			<select class="bill_field" id="license_id" name="license_id">
		 	<? foreach (HoldingLicenseType::find()->all() as $type): ?>
		 		<option value="<?=$type->id?>"><?=$type->name?></option>
		 	<? endforeach ?>
		 	</select>
	 	</div>
	 	<div id="license_controls">
	 		
	 	</div>
 	</div>
</form>

<script type="text/javascript">
	$('#license_controls').empty();
	$('#license_id').change(function(){
		get_html('licenses-controls-change',{'license_id':$('#license_id').val()},function(data){
            $('#license_controls').html(data);
        });
	})
</script>