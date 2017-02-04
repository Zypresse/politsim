<?php

/* @var $this yii\base\View */

?>
<script type="text/javascript">
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-name',
        'name': 'Bill[dataArray][name]',
        'container': '.field-bill-dataarray-name',
        'input': '#bill-dataarray-name',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-nameshort',
        'name': 'Bill[dataArray][nameShort]',
        'container': '.field-bill-dataarray-nameshort',
        'input': '#bill-dataarray-nameshort',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-flag',
        'name': 'Bill[dataArray][flag]',
        'container': '.field-bill-dataarray-flag',
        'input': '#bill-dataarray-flag',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-sharesissued',
        'name': 'Bill[dataArray][sharesIssued]',
        'container': '.field-bill-dataarray-sharesissued',
        'input': '#bill-dataarray-sharesissued',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-sharesprice',
        'name': 'Bill[dataArray][sharesPrice]',
        'container': '.field-bill-dataarray-sharesprice',
        'input': '#bill-dataarray-sharesprice',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    function recalcSumPrice(){
        var count = parseInt($('#bill-dataarray-sharesissued').val()),
            price = parseFloat($('#bill-dataarray-sharesprice').val());
            
        $('#company-registration-cost').text(number_format(count*price, 2, '.', ' '));
    }
    $('#bill-dataarray-sharesissued').change(recalcSumPrice);
    $('#bill-dataarray-sharesprice').change(recalcSumPrice);
    
</script>
