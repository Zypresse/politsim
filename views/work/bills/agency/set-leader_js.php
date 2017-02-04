<?php

use app\models\politics\constitution\ConstitutionArticleType;

/* @var $this yii\base\View */

?>

<script type="text/javascript">
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-agencyid',
        'name': 'Bill[dataArray][agencyId]',
        'container': '.field-bill-dataarray-agencyid',
        'input': '#bill-dataarray-agencyid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-value',
        'name': 'Bill[dataArray][value]',
        'container': '.field-bill-dataarray-value',
        'input': '#bill-dataarray-value',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    function onFormChange() {
        var agencyId = parseInt($('#bill-dataarray-agencyid').val());
        get_json('agency/constitution-value', {
            agencyId: agencyId,
            type: <?= ConstitutionArticleType::LEADER_POST ?>
        }, function(data) {
            $('#bill-dataarray-value').val(data.result.value).attr('selected', 'selected');
        });
    }
    
    $('#bill-dataarray-agencyid').change(onFormChange);
    $(function(){
        onFormChange();
    });
    
</script>
