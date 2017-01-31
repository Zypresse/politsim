<?php

use app\models\politics\constitution\ConstitutionArticleType;

/* @var $this yii\base\View */

?>
<script type="text/javascript">
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-cityid',
        'name': 'Bill[dataArray][cityId]',
        'container': '.field-bill-dataarray-cityid',
        'input': '#bill-dataarray-cityid',
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
        var cityId = parseInt($('#bill-dataarray-cityid').val());
        get_json('city/constitution-value', {
            cityId: cityId,
            type: <?= ConstitutionArticleType::LEADER_POST ?>
        }, function(data) {
            $('#bill-dataarray-value').val(data.result.value).attr('selected', 'selected');
        });
    }
    
    $('#bill-dataarray-cityid').change(onFormChange);
    $(function(){
        onFormChange();
    });
    
</script>
