<?
    use app\components\MyHtmlHelper;
?>
<h3>Создание страны-сателлита</h3>
<form class="form-horizontal">
	<div class="control-group">	
		<label class="control-label" for="bill_new_name" >Название</label>
		<div class="controls">
		 	<input type="text" class="bill_field" id="bill_new_name" name="new_name" >
	 	</div>
        </div>
    	<div class="control-group">	
	 	<label class="control-label" for="bill_new_short_name" >Короткое название</label>
		<div class="controls">
		 	<input type="text" class="bill_field" id="bill_new_short_name" name="new_short_name" >
	 	</div>
 	</div>
        <div class="control-group">	
		<label class="control-label" for="bill_region_code" >Столица</label>
		<div class="controls">
			<select class="bill_field" id="bill_region_code" name="new_capital">
		 	<? foreach ($user->state->regions as $region): ?>
                            <? if ($region->code != $user->state->capital): ?>
		 		<option value="<?=$region->code?>"><?=$region->city?></option>
                            <? endif ?>
		 	<? endforeach ?>
			</select>
	 	</div>
        </div>
        <div class="control-group">	
		<label class="control-label" for="core_id" >Территории</label>
		<div class="controls">
                    <? 
                        $cores = [['Не выделять',[]]];
                        foreach ($user->state->regions as $region) {
                            if ($region->code != $user->state->capital) {
                                foreach ($region->cores as $core) {
                                    if (!(isset($cores[$core->id]))) {
                                        $cores[$core->id] = [$core->name, [$region->name]];
                                    } else {
                                        $cores[$core->id][1][] = $region->name;
                                    }
                                }
                            }
                        }                            
//                        var_dump($cores);
                    ?>                    
                    <select class="bill_field" id="core_id" name="core_id">
                    <? 
                        foreach ($cores as $id => $core): 
                            list($name,$regions) = $core;
                    ?>
                        <option data-regions-list="<?=implode(", ",$regions)?>" value="<?=$id?>"><?=$name?></option>
                    <? endforeach ?>
                    </select>
                    <div id="cores_info" style="display: none">
                        Новому государству отойдут следующие регионы: <span id="satellit_region_list" ></span>
                    </div>
	 	</div>
        </div>
</form>

<script type="text/javascript">
    
    var update_satellit_region_list = function() {
        var regions = $('#core_id').find(':selected').data('regionsList');
        if (regions) {
            $('#satellit_region_list').text(regions);
            $('#cores_info').show();
        } else {
            $('#cores_info').hide();
        }
    };
    
    $('#core_id').change(update_satellit_region_list);
    $(update_satellit_region_list);
    
</script>