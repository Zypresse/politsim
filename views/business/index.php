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
                <hr>
                <?=BusinessViewAsWidget::widget()?>
            </div>
            <div id="shares-list" class="box-body">
                <p><?=Yii::t('app', 'Loading...')?></p>
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
                            <button class="btn btn-block btn-primary"><i class="fa fa-briefcase"></i> <?=Yii::t('app', 'Create new company in state {0}', [Html::encode($viewer->tile->region->state->name)])?></button>
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

    function viewAsUtrChanged(val) {
        $('#shares-list').html('<p><?=Yii::t('app', 'Loading...')?></p>');
        get_html('business/shares', {utr: val}, function(data) {
            $('#shares-list').html(data);
        });
    }
    
</script>
