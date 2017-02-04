<?php

use app\components\widgets\BusinessViewAsWidget,
    app\components\LinkCreator,
    yii\helpers\Html;

/* @var $this yii\base\View */
/* @var $viewer app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Your business')?>
    </h1>
    <ol class="breadcrumb">
        <li><?= LinkCreator::userLink($viewer)?></li>
        <li class="active"><i class="fa fa-briefcase"></i> <?=Yii::t('app', 'Business')?></li>
    </ol>
</section>
<section class="content">
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><?=Yii::t('app', 'Shares')?></h3>
            </div>
            <div class="box-body">
                <?=BusinessViewAsWidget::widget()?>
                <div id="shares-list">
                    <p><?=Yii::t('app', 'Loading...')?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?=Yii::t('app', 'Create company')?></h3>
            </div>
            <div class="box-body">
                <?php if ($viewer->tile): ?>
                    <?php if ($viewer->tile->region && $viewer->tile->region->state): ?>
                        <?php if ($viewer->tile->region->state->isCompaniesCreatingAllowedFor($viewer)): ?>
                            <button class="btn btn-block btn-primary" id="btn-create-company" ><i class="fa fa-briefcase"></i> <?=Yii::t('app', 'Create new company in state {0}', [Html::encode($viewer->tile->region->state->name)])?></button>
                        <?php else: ?>
                            <p><?=Yii::t('app', 'Creating company in state {0} is not allowed', [Html::encode($viewer->tile->region->state->name)])?></p>
                        <?php endif ?>
                    <?php else: ?>
                        <p><?=Yii::t('app', 'You can not create new company in region with no goverment')?></p>
                    <?php endif ?>
                <?php else: ?>
                    <p><?=Yii::t('app', 'You have not residence and can not create new company')?></p>
                <?php endif ?>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">

    function loadShares(utr) {
        $('#shares-list').html('<p><?=Yii::t('app', 'Loading...')?></p>');
        get_html('business/shares', {utr: utr}, function(data) {
            $('#shares-list').html(data);
        });
    }
    
    $('#select-using-utr').change(function(){
        loadShares($('#select-using-utr').val());
    });
    
    $(function(){
        loadShares(viewAsUtr);
    });
    
    
    $('#btn-create-company').click(function(){
        var buttons = '<button class="btn btn-primary" onclick="$(\'#create-company-form\').yiiActiveForm(\'submitForm\')" ><?=Yii::t('app', 'Create')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Cancel')?></button>';
        createAjaxModal(
            'business/create-company-form', {}, 
            '<?=Yii::t('app', 'Create private company')?>',
            buttons
        );
    });
    
</script>
