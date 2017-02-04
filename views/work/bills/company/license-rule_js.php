<?php

/* @var $this yii\base\View */

?>
<script type="text/javascript">
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-protoid',
        'name': 'Bill[dataArray][protoId]',
        'container': '.field-bill-dataarray-protoid',
        'input': '#bill-dataarray-protoid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-whichcompaniesallowed',
        'name': 'Bill[dataArray][whichCompaniesAllowed]',
        'container': '.field-bill-dataarray-whichcompaniesallowed',
        'input': '#bill-dataarray-whichcompaniesallowed',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-isneedconfirmation',
        'name': 'Bill[dataArray][isNeedConfirmation]',
        'container': '.field-bill-dataarray-isneedconfirmation',
        'input': '#bill-dataarray-isneedconfirmation',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-priceforresidents',
        'name': 'Bill[dataArray][priceForResidents]',
        'container': '.field-bill-dataarray-priceforresidents',
        'input': '#bill-dataarray-priceforresidents',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-pricefornonresidents',
        'name': 'Bill[dataArray][priceForNonresidents]',
        'container': '.field-bill-dataarray-pricefornonresidents',
        'input': '#bill-dataarray-pricefornonresidents',
        'error': '.help-block',
        'enableAjaxValidation': true
    });    
    
    function onProtoChange() {
        var type = parseInt($('#bill-dataarray-protoid').val());
        get_json('state/license-rule-info', {id: $('#bill-stateid').val(), protoId: type}, function(data){
            if (data.result) {
                $('#bill-dataarray-whichcompaniesallowed').val(data.result.whichCompaniesAllowed).attr("selected", "selected");
                $('#bill-dataarray-isneedconfirmation').val(data.result.isNeedConfirmation ? 1 : 0).attr("selected", "selected");
                $('#bill-dataarray-priceforresidents').val(data.result.priceForResidents);
                $('#bill-dataarray-pricefornonresidents').val(data.result.priceForNonresidents);
            }
        });
    }
        
    $('#bill-dataarray-protoid').change(onProtoChange);
    $(function(){
        onProtoChange();
    });
        
</script>
