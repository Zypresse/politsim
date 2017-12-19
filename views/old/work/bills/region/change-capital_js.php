<?php

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
        'id': 'bill-dataarray-cityid',
        'name': 'Bill[dataArray][cityId]',
        'container': '.field-bill-dataarray-cityid',
        'input': '#bill-dataarray-cityid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    function loadCities() {
        $('#bill-dataarray-cityid').empty();
        get_json('region/cities', {id: $('#bill-dataarray-regionid').val()}, function(data){
            for (var i = 0, l = data.result.length; i < l; i++) {
                $('#bill-dataarray-cityid').append('<option value="'+data.result[i].id+'">'+data.result[i].name+'</option>');
            }
        });
    }
    
    $('#bill-dataarray-regionid').change(loadCities);
    $(function(){
        loadCities();
    });
    
</script>
