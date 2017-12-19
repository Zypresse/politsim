<?php

/* @var $this yii\base\View */

?>
<script type="text/javascript">
    
    $('#company-new-decision-list-form-modal-label').append(' — <?= Yii::t('app', 'Create new currency')?>');
    
    $form.yiiActiveForm('add', {
        'id': 'companydecision-dataarray-name',
        'name': 'CompanyDecision[dataArray][name]',
        'container': '.field-companydecision-dataarray-name',
        'input': '#companydecision-dataarray-name',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'companydecision-dataarray-nameshort',
        'name': 'CompanyDecision[dataArray][nameShort]',
        'container': '.field-companydecision-dataarray-nameshort',
        'input': '#companydecision-dataarray-nameshort',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'companydecision-dataarray-exchangerate',
        'name': 'CompanyDecision[dataArray][exchangeRate]',
        'container': '.field-companydecision-dataarray-exchangerate',
        'input': '#companydecision-dataarray-exchangerate',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    function onNameShortChange() {
        $('#new-currency-nameshort').text($('#companydecision-dataarray-nameshort').val());
    }
    
    function onRateChange() {
        $('#rate-count-currency').text(number_format($('#companydecision-dataarray-exchangerate').val(), 2, '.', ' '));
        $('#rate-count-international').text(1);
    }
    
    $('#companydecision-dataarray-nameshort').change(onNameShortChange);
    $('#companydecision-dataarray-exchangerate').change(onRateChange);
    
    
</script>
