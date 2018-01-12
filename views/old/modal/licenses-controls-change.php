<?php
    use app\components\MyHtmlHelper;
?>

<div class="control-group">	
    <label class="control-label" for="is_only_goverment" >Гос. монополия</label>
    <div class="controls">
        <input class="bill_field" type="checkbox" name="is_only_goverment" id="is_only_goverment" value="1" <?= $stateLicense->is_only_goverment ? "checked='checked'" : "" ?> >
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="is_need_confirm" >Обязательное подтвержение заявки министром</label>
    <div class="controls">
        <input class="bill_field" type="checkbox" name="is_need_confirm" id="is_need_confirm" value="1" <?= $stateLicense->is_need_confirm ? "checked='checked'" : "" ?> >
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="cost" >Стоимость лицензии</label>
    <div class="controls">
        <input class="bill_field" type="number" name="cost" id="cost" value="<?=$stateLicense->cost?>" > <?=MyHtmlHelper::icon('money')?>
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="is_need_confirm_noncitizens" >Обязательное подтвержение заявки министром для иностранных фирм</label>
    <div class="controls">
        <input class="bill_field" type="checkbox" name="is_need_confirm_noncitizens" id="is_need_confirm_noncitizens" value="1" <?= $stateLicense->is_need_confirm_noncitizens ? "checked='checked'" : "" ?> >
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="cost_noncitizens" >Стоимость лицензии</label>
    <div class="controls">
        <input class="bill_field" type="number" name="cost_noncitizens" id="cost_noncitizens" value="<?=$stateLicense->cost_noncitizens?>" > <?=MyHtmlHelper::icon('money')?>
    </div>
</div>