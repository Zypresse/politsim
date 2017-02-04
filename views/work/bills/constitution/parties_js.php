<?php

use app\models\politics\constitution\articles\statesonly\Parties;

/* @var $this yii\base\View */

?>
<script type="text/javascript">
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-value',
        'name': 'Bill[dataArray][value]',
        'container': '.field-bill-dataarray-value',
        'input': '#bill-dataarray-value',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-value2',
        'name': 'Bill[dataArray][value2]',
        'container': '.field-bill-dataarray-value2',
        'input': '#bill-dataarray-value2',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-value3',
        'name': 'Bill[dataArray][value3]',
        'container': '.field-bill-dataarray-value3',
        'input': '#bill-dataarray-value3',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    function onFormChange() {
        var type = parseInt($('#bill-dataarray-value').val());
        switch (type) {
            case <?=Parties::ALLOWED?>:
            case <?=Parties::NEED_CONFIRM?>:
                $('.field-bill-dataarray-value2').hide();
                $('.field-bill-dataarray-value3').show();
                break;
            case <?=Parties::ONLY_RULING?>:
                $('.field-bill-dataarray-value2').show();
                $('.field-bill-dataarray-value3').hide();
                break;
            default:
                $('.field-bill-dataarray-value2').hide();
                $('.field-bill-dataarray-value3').hide();
                break;
                
        }
    }
    
    $('#new-bill-form .form-control').change(onFormChange);
    $(function(){
        onFormChange();
    });
    
</script>
