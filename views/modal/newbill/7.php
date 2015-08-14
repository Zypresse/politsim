<?
    use app\components\MyHtmlHelper,
        app\models\GovermentFieldType;
?>
<p>Внесение поправки в конституцию</p>
<form class="form-horizontal">
    <div class="control-group">
        <select class="bill_field" id="bill7_goverment_field_type" name="goverment_field_type">
            <? foreach (GovermentFieldType::find()->where(['hide' => 0])->all() as $type) { ?>
            <option value="<?=$type->id?>"><?=$type->name?></option>
            <? } ?>
        </select>
    </div>
    <div class="control-group" id="goverment_field_value_block">
    </div>
</form>

<script type="text/javascript">

var load_field_input = function() {
    $('#goverment_field_value_block').empty();
    get_html("goverment-field-value", {type: $('#bill7_goverment_field_type').val()}, function(data){
        $("#goverment_field_value_block").html(data);
    });
};

$('#bill7_goverment_field_type').change(load_field_input);

$(load_field_input);

</script>