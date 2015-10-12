<?

use app\components\MyHtmlHelper,
    app\models\licenses\proto\LicenseProto;
?>
<h3>Смена порядка выдачи лицензий</h3>
<form class="form-horizontal">
    <div class="control-group">	
        <label class="control-label" for="license_proto_id" >Тип лицензии</label>
        <div class="controls">
            <select class="bill_field" id="license_id" name="license_proto_id">
                <? foreach (LicenseProto::find()->all() as $type): ?>
                    <option value="<?= $type->id ?>"><?= $type->name ?></option>
                <? endforeach ?>
            </select>
        </div>
    </div>
    <div id="license_controls">

    </div>
</form>

<script type="text/javascript">

    var load_licenses_controls = function() {
        get_html('licenses-controls-change', {'license_proto_id': $('#license_id').val()}, function (data) {
            $('#license_controls').html(data);
        });
    }

    $('#license_controls').empty();
    $('#license_id').change(load_licenses_controls);
    $(load_licenses_controls);
</script>