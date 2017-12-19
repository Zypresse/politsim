<?php

use app\models\politics\constitution\articles\postsonly\DestignationType;

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
        'id': 'bill-dataarray-destignationvalue',
        'name': 'Bill[dataArray][destignationValue]',
        'container': '.field-bill-dataarray-destignationvalue',
        'input': '#bill-dataarray-destignationvalue',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-destignationvalue2',
        'name': 'Bill[dataArray][destignationValue2]',
        'container': '.field-bill-dataarray-destignationvalue2',
        'input': '#bill-dataarray-destignationvalue2',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-destignationvalue3',
        'name': 'Bill[dataArray][destignationValue3]',
        'container': '.field-bill-dataarray-destignationvalue3',
        'input': '#bill-dataarray-destignationvalue3',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-tovalue',
        'name': 'Bill[dataArray][toValue]',
        'container': '.field-bill-dataarray-tovalue',
        'input': '#bill-dataarray-tovalue',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-tevalue',
        'name': 'Bill[dataArray][teValue]',
        'container': '.field-bill-dataarray-tevalue',
        'input': '#bill-dataarray-tevalue',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-tevalue2',
        'name': 'Bill[dataArray][teValue2]',
        'container': '.field-bill-dataarray-tevalue2',
        'input': '#bill-dataarray-tevalue2',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-tevalue3',
        'name': 'Bill[dataArray][teValue3]',
        'container': '.field-bill-dataarray-tevalue3',
        'input': '#bill-dataarray-tevalue3',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    function loadDestignatorList(value) {
        switch (value) {
            case <?= DestignationType::BY_OTHER_POST ?>:
                $('#bill-dataarray-destignationvalue2').html($('#destignator-posts').html());
                break;
            case <?= DestignationType::BY_AGENCY_ELECTION ?>:
                $('#bill-dataarray-destignationvalue2').html($('#destignator-agencies').html());
                break;
            case <?= DestignationType::BY_DISTRICT_ELECTION ?>:
                $('#bill-dataarray-destignationvalue2').html($('#destignator-districts').html());
                break;
            case <?= DestignationType::BY_REGION_ELECTION ?>:
                $('#bill-dataarray-destignationvalue2').html($('#destignator-regions').html());
                break;
            case <?= DestignationType::BY_CITY_ELECTION ?>:
                $('#bill-dataarray-destignationvalue2').html($('#destignator-cities').html());
                break;
            default:
                $('#bill-dataarray-destignationvalue2').empty();
                break;
        }
    }
    
    function onFormChange() {
    
        var value = parseInt($('#bill-dataarray-destignationvalue').val());
    
        loadDestignatorList(value);
                
        switch (value) {
            case <?= DestignationType::BY_PRECURSOR ?>:
                $('.field-bill-dataarray-destignationvalue2').hide();
                $('.field-bill-dataarray-destignationvalue3').hide();
                
                $('.field-bill-dataarray-tovalue').hide();
                $('.field-bill-dataarray-tevalue').hide();
                $('.field-bill-dataarray-tevalue2').hide();
                $('.field-bill-dataarray-tevalue3').hide();
                break;  
            case <?= DestignationType::BY_OTHER_POST ?>:
                $('.field-bill-dataarray-destignationvalue2').show();
                $('.field-bill-dataarray-destignationvalue3').hide();
                
                $('.field-bill-dataarray-tovalue').hide();
                $('.field-bill-dataarray-tevalue').hide();
                $('.field-bill-dataarray-tevalue2').hide();
                $('.field-bill-dataarray-tevalue3').hide();
                break;
            case <?= DestignationType::BY_STATE_ELECTION ?>:
                $('.field-bill-dataarray-destignationvalue2').hide();
                $('.field-bill-dataarray-destignationvalue3').show();
                
                $('.field-bill-dataarray-tovalue').show();
                $('.field-bill-dataarray-tevalue').show();
                $('.field-bill-dataarray-tevalue2').show();
                $('.field-bill-dataarray-tevalue3').show();
                break;
            default:
                $('.field-bill-dataarray-destignationvalue2').show();
                $('.field-bill-dataarray-destignationvalue3').show();
                
                $('.field-bill-dataarray-tovalue').show();
                $('.field-bill-dataarray-tevalue').show();
                $('.field-bill-dataarray-tevalue2').show();
                $('.field-bill-dataarray-tevalue3').show();
                break;
        }
    }
    
    $('#bill-dataarray-destignationvalue').change(onFormChange);
    $(function(){
        onFormChange();
    });
    
</script>
