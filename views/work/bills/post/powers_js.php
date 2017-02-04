<?php

use app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\Powers,
    app\models\politics\constitution\articles\postsonly\powers\Parties,
    app\models\politics\constitution\articles\postsonly\powers\Bills,
    app\models\politics\constitution\articles\postsonly\powers\Licenses;

/* @var $this yii\base\View */

?>
<script type="text/javascript">
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-postid',
        'name': 'Bill[dataArray][postId]',
        'container': '.field-bill-dataarray-postid',
        'input': '#bill-dataarray-postid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-bills',
        'name': 'Bill[dataArray][bills]',
        'container': '.field-bill-dataarray-bills',
        'input': '#bill-dataarray-bills',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-parties',
        'name': 'Bill[dataArray][parties]',
        'container': '.field-bill-dataarray-parties',
        'input': '#bill-dataarray-parties',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-licenses-value',
        'name': 'Bill[dataArray][licenses][value]',
        'container': '.field-bill-dataarray-licenses-value',
        'input': '#bill-dataarray-licenses-value',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-licenses-value2',
        'name': 'Bill[dataArray][licenses][value2]',
        'container': '.field-bill-dataarray-licenses-value2',
        'input': '#bill-dataarray-licenses-value2',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
        
    function onPostChange() {
        
        var postId = parseInt($('#bill-dataarray-postid').val());
        get_json('post/constitution', {
            postId: postId,
            types: '<?= ConstitutionArticleType::POWERS ?>:<?=Powers::BILLS?>,<?= ConstitutionArticleType::POWERS ?>:<?=Powers::PARTIES?>,<?= ConstitutionArticleType::POWERS ?>:<?=Powers::LICENSES?>'
        }, function(data) {
            
            var bills, parties, licensesValue, licensesValue2;
            for (var i = 0; i < data.result.length; i++) {
                var subType = parseInt(data.result[i].subType);
                switch (subType) {
                    case <?= Powers::BILLS ?>:
                        bills = data.result[i].value ? parseInt(data.result[i].value) : 0;
                        break;
                    case <?= Powers::PARTIES ?>:
                        parties = data.result[i].value ? parseInt(data.result[i].value) : 0;
                        break;
                    case <?= Powers::LICENSES ?>:
                        licensesValue = data.result[i].value ? parseInt(data.result[i].value) : 0;
                        licensesValue2 = data.result[i].value2 ? data.result[i].value2 : [];
                        break;
                }
            }
            
            $('#bill-dataarray-bills input').prop('checked', false);
            if (bills & <?= Bills::VOTE ?>) {
                $('#bill-dataarray-bills input[value=<?= Bills::VOTE ?>]').prop('checked', true);;
            }
            if (bills & <?= Bills::CREATE ?>) {
                $('#bill-dataarray-bills input[value=<?= Bills::CREATE ?>]').prop('checked', true);;
            }
            if (bills & <?= Bills::ACCEPT ?>) {
                $('#bill-dataarray-bills input[value=<?= Bills::ACCEPT ?>]').prop('checked', true);;
            }
            if (bills & <?= Bills::VETO ?>) {
                $('#bill-dataarray-bills input[value=<?= Bills::VETO ?>]').prop('checked', true);;
            }
            if (bills & <?= Bills::DISCUSS ?>) {
                $('#bill-dataarray-bills input[value=<?= Bills::DISCUSS ?>]').prop('checked', true);;
            }
            
            $('#bill-dataarray-parties input').prop('checked', false);
            if (parties & <?= Parties::ACCEPT ?>) {
                $('#bill-dataarray-parties input[value=<?= Parties::ACCEPT ?>]').prop('checked', true);;
            }
            if (parties & <?= Parties::REVOKE ?>) {
                $('#bill-dataarray-parties input[value=<?= Parties::REVOKE ?>]').prop('checked', true);;
            }
            
            $('#bill-dataarray-licenses-value input').prop('checked', false);
            if (licensesValue & <?= Licenses::ACCEPT ?>) {
                $('#bill-dataarray-licenses-value input[value=<?= Licenses::ACCEPT ?>]').prop('checked', true);;
            }
            if (licensesValue & <?= Licenses::REVOKE ?>) {
                $('#bill-dataarray-licenses-value input[value=<?= Licenses::REVOKE ?>]').prop('checked', true);;
            }
            
            $('#bill-dataarray-licenses-value2 input').prop('checked', false);
            for (var i = 0, l = licensesValue2.length; i < l; i++) {
                $('#bill-dataarray-licenses-value2 input[value='+licensesValue2[i]+']').prop('checked', true);;
            }
            
        });
    }
        
    $('#bill-dataarray-postid').change(onPostChange);
    $(function(){
        onPostChange();
    });
    
</script>
