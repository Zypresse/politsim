<?php

/* @var $this yii\base\View */

?>
<script type="text/javascript">
    
    $('#company-new-decision-list-form-modal-label').append(' — <?= Yii::t('app', 'Set director')?>');
    
    $form.yiiActiveForm('add', {
        'id': 'companydecision-dataarray-userid',
        'name': 'CompanyDecision[dataArray][userId]',
        'container': '.field-companydecision-dataarray-userid',
        'input': '#companydecision-dataarray-userid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $(function(){
        
        $( "#search-user-name" ).autocompleteUsersSearch({
            source: "/user/global-find",
            minLength: 2,
            select: function( event, ui ) {
                $('#companydecision-dataarray-userid').val(ui.item.id).change();
            }
        });
        
    });
    
</script>
