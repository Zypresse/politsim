<?php

/* @var $this yii\base\View */
/* @var $shareholder app\models\economics\TaxPayer */
/* @var $company app\models\economics\Company */
/* @var $types array */

?>
<div class="box">
    <div class="box-body">
        <div class="btn-group">
        <?php foreach ($types as $id => $name): ?>
            <button class="btn btn-default new-decision-type-btn" data-id="<?=$id?>">
                <?=$name?>
            </button>
        <?php endforeach ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    $('.new-decision-type-btn').click(function(){
        load_modal(
            'company/new-decision-form',
            {id:<?=$company->id?>, utr:<?=$shareholder->getUtr()?>, protoId:$(this).data('id')},
            'company-new-decision-list-form-modal'
        );
        $('#company-new-decision-list-form-modal .modal-dialog').removeClass('modal-lg');
        $('#new-decision-confirm-btn').removeClass('hide');
    });
    
    $('#company-new-decision-list-form-modal .modal-dialog').addClass('modal-lg');

</script>