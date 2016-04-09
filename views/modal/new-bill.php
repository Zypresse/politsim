<?php 

    use app\components\MyHtmlHelper;
    
?>
<p>Новый законопроект, предлагающий &laquo;<?=htmlspecialchars($bill_type->name)?>&raquo;</p>
<form class="form-horizontal">
<?php foreach ($fields as $field) { ?>
<div class="control-group">
	
		<label class="control-label" for="bill<?=$bill_type->id?>_<?=$field->system_name?>" ><?=htmlspecialchars($field->name)?></label>

		 <div class="controls">
	<?php switch ($field->type) {
		 case 'string': ?>
		 <input type="text" class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>" >
		 <?php break;
		 case 'number': ?>
		 <input type="number" class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>" onchange="$(this).val($(this).val() < 0 ? 0 : parseInt($(this).val()))" >
		 <?php break;
		 case 'color': ?>
		 <input type="text" class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>">
		 <script type="text/javascript" src="/js/spectrum.js"></script>
		 <script>
		 $(function(){

			$("#bill<?=$bill_type->id?>_<?=$field->system_name?>").spectrum({
				preferredFormat: "hex",
			    showInput: true,
			    showPalette: true,
			    clickoutFiresChange: true,
			    //hideAfterPaletteSelect:true,
			    showButtons: false,

			    palette: [
			        [/*"rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)", "rgb(153, 153, 153)","rgb(183, 183, 183)",*/
			        "rgb(204, 204, 204)", "rgb(217, 217, 217)", "rgb(239, 239, 239)", "rgb(243, 243, 243)", "rgb(255, 255, 255)"],
			        ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
			        "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
			        ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
			        "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
			        "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
			        "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
			        "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
			        "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
			        "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
			        "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
			        "rgb(133, 32, 12)", "rgb(153, 0, 0)", "rgb(180, 95, 6)", "rgb(191, 144, 0)", "rgb(56, 118, 29)",
			        "rgb(19, 79, 92)", "rgb(17, 85, 204)", "rgb(11, 83, 148)", "rgb(53, 28, 117)", "rgb(116, 27, 71)",
			        "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
			        "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
			    ]
			});
		 })
		 </script>
		 <?php break;
		 case 'money': ?>
                 <input type="number" class="bill_field" value="0" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>" onchange="$(this).val($(this).val() < 0 ? 0 : parseInt($(this).val()))" > <?=MyHtmlHelper::icon('money')?>
		 <?php break;
		 case 'regions':
		 case 'regions_all': ?>
		 <select class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>">
		 	<?php foreach ($additional_data['regions'] as $region) { ?>
		 	<option value="<?=$region->code?>"><?=$region->name?></option>
		 	<?php } ?>
		 </select>
		 <?php break;
		 case 'cities':
		 case 'cities_all': ?>
		 <select class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>">
		 	<?php foreach ($additional_data['regions'] as $region) { ?>
		 	<option value="<?=$region->code?>"><?=$region->city?></option>
		 	<?php } ?>
		 </select>
		 <?php break;
                 case 'elected_variants': if (sizeof($additional_data['elected_variants'])) { ?>
		 <select class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>">
		 	<?php foreach ($additional_data['elected_variants'] as $type) { ?>
		 	<option value="<?=$type['key']?>"><?=$type['name']?></option>
		 	<?php } ?>
		 </select>
                 <?php } break;
		 case 'cores': if (sizeof($additional_data['cores'])) { ?>
		 <select class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>">
		 	<?php foreach ($additional_data['cores'] as $i => $type) { ?>
                        <?php if (is_object($type)) { ?><option value="<?=$type->id?>"><?=$type->name?></option><?php } else { ?><option value="<?=$i?>"><?=$type?></option><?php } ?>
		 	<?php } ?>
		 </select>
                 <?php } break;
		 case 'legislature_types': ?>
		 <select class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>">
		 	<?php foreach ($additional_data['legislature_types'] as $type) { ?>
		 	<option value="<?=$type['id']?>"><?=$type['display_name']?></option>
		 	<?php } ?>
		 </select>
		 <?php break;
		 case 'goverment_field_types': ?>
		 <select onchange="change_fields()" class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>">
		 	<?php foreach ($additional_data['goverment_field_types'] as $type) { ?>
		 	<option value="<?=$type->id?>"><?=$type->name?></option>
		 	<?php } ?>
		 </select>
		 <?php break;
		 case 'goverment_field_value': ?>
		 		<?php switch ($additional_data['goverment_field_types'][0]->type) {
		 			case "checkbox": ?>
				  <input type="checkbox" class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>">
				<?php break;
					case "integer":
                                        case "number": ?>
					<input type="number" class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>">
				<?php break;
					case "org_dest_members": ?>
					<select class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>">
						<option value="dest_by_leader">Назначаются напрямую лидером</option>
						<option value="nation_party_vote">Голосование населения за партии</option>
						<option value="nation_one_party_vote">Голосование населения за членов единственной партии</option>
					</select>
				<?php break;
					case "org_dest_leader": ?>
					<select class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>">
						<option value="unlimited">Пожизненно</option>
						<option value="nation_individual_vote">Голосование населения за кандидатов</option>
						<option value="nation_party_vote">Голосование населения за партии</option>
						<!--<option value="other_org_vote">Голосуют члены другой организации</option>-->
						<option value="org_vote">Голосуют члены этой же организации</option>
					</select>
				<?php break; default: ?>
				  <input type="text" class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>">
				<?php break; } ?>
		 	
		 <?php break;
                 case 'checkbox':
                ?>
                    <input type="checkbox" value="1" class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>">
                <?php   
                 break;
                case 'licenses':
                ?>
                <select class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>">
		 	<?php foreach ($additional_data['licenses'] as $license) { ?>
		 	<option value="<?=$license->id?>"><?=$license->name?></option>
		 	<?php } ?>
		 </select>
                <?php                     break;
                case 'orgs': if (sizeof($additional_data['orgs'])) { 
                ?>
                <select class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>">
		 	<?php foreach ($additional_data['orgs'] as $org) { ?>
		 	<option value="<?=$org->id?>"><?=$org->name?></option>
		 	<?php } ?>
		 </select>
                <?php }
                    break;
		 default: ?>
		 	<input type="text" class="bill_field" id="bill<?=$bill_type->id?>_<?=$field->system_name?>" name="<?=$field->system_name?>" >
		 <?php break; 
		 } ?>
		 </div>
	
</div>
<?php } ?>

</form>

<script>
	bill_id = <?=$bill_type->id?>;
<?php if (isset($additional_data['goverment_field_types'])) { ?>
	function change_fields() {
		var a = {
		<?php foreach ($additional_data['goverment_field_types'] as $i => $type) { ?>
	 	<?=$i ? ',' : ''?><?=$type->id?>:'<?=$type->type?>'
	 	<?php } ?>
		}

		var type = $('#bill<?=$bill_type->id?>_goverment_field_type').val();
		$par = $('#bill<?=$bill_type->id?>_goverment_field_value').parent();
		$('#bill<?=$bill_type->id?>_goverment_field_value').remove();
		switch (a[type]) {
			case 'checkbox':
				$par.html("<input type=\"checkbox\" class=\"bill_field\" id=\"bill<?=$bill_type->id?>_goverment_field_value\" name=\"goverment_field_value\" value=1>");
			break;
			case 'integer':
				$par.html("<input type=\"number\" class=\"bill_field\" id=\"bill<?=$bill_type->id?>_goverment_field_value\" name=\"goverment_field_value\">");
			break;
			case 'org_dest_members':
				$par.html('<select class="bill_field" id="bill<?=$bill_type->id?>_goverment_field_value" name="goverment_field_value">						<option value="dest_by_leader">Назначаются напрямую лидером</option>						<option value="nation_party_vote">Голосование населения за партии</option>						<option value="nation_one_party_vote">Голосование населения за членов единственной партии</option>					</select>');
			break;
			case 'org_dest_leader':
				$par.html('<select class="bill_field" id="bill<?=$bill_type->id?>_goverment_field_value" name="goverment_field_value">						<option value="unlimited">Пожизненно</option>						<option value="nation_individual_vote">Голосование населения за кандидатов</option>						<option value="nation_party_vote">Голосование населения за партии</option>						<option value="other_org_vote">Голосуют члены другой организации</option>						<option value="org_vote">Голосуют члены этой же организации</option>					</select>');
			break;
			default:
				$par.html("<input type=\"text\" class=\"bill_field\" id=\"bill<?=$bill_type->id?>_goverment_field_value\" name=\"goverment_field_value\">");
			break;
		}

	}
<?php } ?>
</script>