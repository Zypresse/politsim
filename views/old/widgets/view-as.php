<?php

use yii\helpers\Html;

/* @var $user \app\models\User */
/* @var $selectedUtr mixed */
/* @var $utrs array */

?>
<div class="form-group">
    <label class="inline" for="#select-using-utr" ><?=Yii::t('app', 'View as:')?></label>
    &nbsp;
    <?=Html::dropDownList('select-using-utr', $selectedUtr, $utrs, [
        'id' => 'select-using-utr',
        'class' => 'form-control inline view-as-select',
    ])?>
</div>

<script type="text/javascript">
    
    viewAsUtr = docCookies.getItem('viewAsUtr');
    
    $('#select-using-utr').change(function(){
        setViewAsUtr($('#select-using-utr').val());
    });
    
    setViewAsUtr(viewAsUtr);
    $('#select-using-utr').val(viewAsUtr).attr('selected', 'selected');
    
</script>
