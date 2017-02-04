<?php

/* @var $this yii\base\View */

?>
<script type="text/javascript">
    
    $('#company-new-decision-list-form-modal-label').append(' — <?= Yii::t('app', 'Get new license')?>');
    
    $form.yiiActiveForm('add', {
        'id': 'companydecision-dataarray-stateid',
        'name': 'CompanyDecision[dataArray][stateId]',
        'container': '.field-companydecision-dataarray-stateid',
        'input': '#companydecision-dataarray-stateid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'companydecision-dataarray-protoid',
        'name': 'CompanyDecision[dataArray][protoId]',
        'container': '.field-companydecision-dataarray-protoid',
        'input': '#companydecision-dataarray-protoid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    function onFormChange() {
        
        var stateId = parseInt($('#companydecision-dataarray-stateid').val()),
            protoId = parseInt($('#companydecision-dataarray-protoid').val());
    
        get_json('state/license-rule-info', {id: stateId, protoId: protoId}, function(data) {
            var cost = parseFloat(stateId === parseInt($('#companydecision-stateid').val()) ? data.result.priceForResidents : data.result.priceForNonresidents);
            $('#license-cost').text(number_format(cost, 2, '.', ' '));
            if (data.result.isNeedConfirmation) {
                $('#license-need-confirmation-alert').show();
            } else {
                $('#license-need-confirmation-alert').hide();
            }
        });
    }
    
    $('#companydecision-dataarray-stateid').change(onFormChange);
    $('#companydecision-dataarray-protoid').change(onFormChange);
    
    $(function(){
        onFormChange();
    });
    
</script>
