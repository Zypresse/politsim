<?php
/* @var $holding app\models\Holding */
/* @var $states app\models\State */
/* @var $licenses app\models\licenses\LicenseProto[] */

use app\components\MyHtmlHelper;
?>
<div class="control-group">
    <label class="control-label" for="#new_license_state_id">Государство</label>
    <div class="controls">            
        <select id="new_license_state_id">
<?php foreach ($states as $state):
    ?>
                <option <?php if ($state->id === $holding->state_id): ?> selected="selected" <?php endif ?> id="state_option<?= $state->id ?>" value="<?= $state->id ?>" ><?= $state->name ?></option>       
            <?php endforeach ?>
        </select>
    </div>
    <label class="control-label" for="#new_license_id">Лицензия</label>
    <div class="controls">
        <select id="new_license_id">
<?php $state = ($holding->state) ? $holding->state : $states[0];
foreach ($licenses as $license) {
    $stateLicense = null;
    $allowed = true;
    foreach ($holding->licenses as $hl) {
        if ($license->id === $hl->proto_id) {
            $allowed = false;
            $break;
        }
    }
    if (!$allowed)
        continue;

    foreach ($state->licenses as $sl) {
        if ($license->id === $sl->proto_id) {
            $stateLicense = $sl;
            break;
        }
    }
    $text = "Получение лицензии бесплатно";
    if (!(is_null($stateLicense))) {
        if ($stateLicense->is_only_goverment) {
            if (!$holding->isGosHolding($state->id)) {
                continue;
            }
        }
        if ($stateLicense->cost) {
            $text = number_format($stateLicense->cost, 0, '', ' ') . ' ' . MyHtmlHelper::icon('money');
        }
        if ($stateLicense->is_need_confirm) {
            $text .= "<br>Необходимо подтверждение министра";
        }
    }
    ?>
                <option id="license_option<?= $license->id ?>" value="<?= $license->id ?>" data-text="<?= $text ?>"><?= $license->name ?></option>      
            <?php }
            ?>
        </select>
    </div>
    <p id="license_info"></p>
</div>
<script type="text/javascript">
    
    function updateLicenseInfo() {
        $('#license_info').html($("#license_option" + $('#new_license_id').val()).data('text'));
    }

    $(function(){    
        updateLicenseInfo();
        $('#new_license_id').change(updateLicenseInfo);
        $('#new_license_state_id').change(function () {
            $('#new_license_id').attr('disabled', 'disabled');
            get_html('licenses-options', {'state_id': $(this).val(), 'holding_id':<?= $holding->id ?>}, function (data) {
                $('#new_license_id').html(data);
                $('#new_license_id').removeAttr('disabled');
                updateLicenseInfo();
            });
        })
    })  

</script>