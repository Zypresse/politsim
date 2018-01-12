<?php     

    use app\models\articles\proto\ArticleProto;
    
?>
<p>Внесение поправки в конституцию</p>
<form class="form-horizontal">
    <div class="control-group">
        <select class="bill_field" id="bill_article_proto_id" name="article_proto_id">
            <?php foreach (ArticleProto::find()->where(['hide' => 0])->all() as $type) { ?>
            <option value="<?=$type->id?>"><?=$type->name?></option>
            <?php } ?>
        </select>
    </div>
    <div class="control-group" id="goverment_field_value_block">
    </div>
</form>

<script type="text/javascript">

var load_field_input = function() {
    $('#goverment_field_value_block').empty();
    get_html("goverment-field-value", {'proto_id': $('#bill_article_proto_id').val()}, function(data){
        $("#goverment_field_value_block").html(data);
    });
};

$('#bill_article_proto_id').change(load_field_input);

$(load_field_input);

</script>