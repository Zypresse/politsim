<?php

use yii\helpers\Html,
    app\components\widgets\BusinessViewAsWidget,
    app\components\LinkCreator,
    app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $company app\models\economics\Company */
/* @var $shareholder app\models\economics\TaxPayer */
/* @var $user app\models\User */

?>
<section class="content-header">
    <div class="pull-right">
        <?=BusinessViewAsWidget::widget()?>
    </div>
    <h1>
        <?=Html::encode($company->name)?>
    </h1>
<!--    <ol class="breadcrumb">
        <li class="active"><?=$company->flag ? Html::img($company->flag, ['style' => 'height: 8px; vertical-align: baseline;']) : ''?> <?=Html::encode($company->name)?></li>
    </ol>-->
</section>
<section class="content">
    <div class="row">
        <?php if ($company->flag): ?>
        <div class="col-md-4">
            <div class="box">
                <div class="box-body">
                    <?=Html::img($company->flag, ['class' => 'img-polaroid', 'style' => 'width: 100%'])?>
                </div>
                <div class="box-footer">
                    <em><?=Yii::t('app', 'Company logo')?></em>
                </div>
            </div>
        </div>
        <?php endif ?>
        <div class="col-md-<?=($company->flag)?8:12?>">            
            <div class="box">
                <div class="box-header">
                    <h1>
                        <?=Html::encode($company->name)?>
                         <small>(<?=Html::encode($company->nameShort)?>)</small>
                    </h1>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (!$company->state || $company->state->dateDeleted): ?>
                            <div class="callout callout-danger">
                                <h4><i class="icon fa fa-ban"></i> <?=Yii::t('app', 'Company registered in not-existing state!')?></h4>
                                <p><?= $company->state ? Yii::t('app', 'This company registered in state {0} but this state no more exist', [LinkCreator::stateLink($company->state)]) : Yii::t('app', 'This company registered in unknown state')?></p>
                            </div>
                            <?php endif ?>
                            <div class="callout callout-warning">
                                <h4><i class="icon fa fa-warning"></i> <?=Yii::t('app', 'You can not control this company as {0}!', [LinkCreator::link($shareholder)])?></h4>
                                <p><?=Html::a(Yii::t('app', 'View information about company «{0}»', [Html::encode($company->name)]), ['#!company/view', 'id' => $company->id])?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    
    $('#select-using-utr').change(function(){
        current_page_params.utr = $('#select-using-utr').val();
        reload_page();
    });
    
</script>