<?php

/* @var $this yii\base\View */

?>
<script type="text/javascript">
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-companyid',
        'name': 'Bill[dataArray][companyId]',
        'container': '.field-bill-dataarray-companyid',
        'input': '#bill-dataarray-companyid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-shareholderutr',
        'name': 'Bill[dataArray][shareholderUtr]',
        'container': '.field-bill-dataarray-shareholderutr',
        'input': '#bill-dataarray-shareholderutr',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    
    function onFormChange() {
//        var cityId = parseInt($('#bill-dataarray-cityid').val());
//        get_json('city/constitution-value', {
//            cityId: cityId,
//            type: <?//= ConstitutionArticleType::LEADER_POST ?>
//        }, function(data) {
//            $('#bill-dataarray-value').val(data.result.value).attr('selected', 'selected');
//        });
    }
    
    $('#bill-dataarray-companyid').change(onFormChange);
    $(function(){
        onFormChange();
    });
    
</script>
