<?php

use app\models\politics\constitution\ConstitutionArticleType;

/* @var $this yii\base\View */

?>
<script type="text/javascript">
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-regionid',
        'name': 'Bill[dataArray][regionId]',
        'container': '.field-bill-dataarray-regionid',
        'input': '#bill-dataarray-regionid',
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
        var regionId = parseInt($('#bill-dataarray-regionid').val());
        get_json('region/constitution-value', {
            regionId: regionId,
            type: <?= ConstitutionArticleType::LEADER_POST ?>
        }, function(data) {
            $('#bill-dataarray-value').val(data.result.value).attr('selected', 'selected');
        });
    }
    
    $('#bill-dataarray-regionid').change(onFormChange);
    $(function(){
        onFormChange();
    });
    
</script>
