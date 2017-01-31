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
                        <?php if (!$company->state || $company->state->dateDeleted): ?>
                        <div class="callout callout-danger">
                            <h4><i class="icon fa fa-ban"></i> <?=Yii::t('app', 'Company registered in not-existing state!')?></h4>
                            <p><?= $company->state ? Yii::t('app', 'This company registered in state {0} but this state no more exist', [LinkCreator::stateLink($company->state)]) : Yii::t('app', 'This company registered in unknown state')?></p>
                        </div>
                        <?php endif ?>
                        <div class="col-md-6">
                            <p>
                                <strong><?=Yii::t('app', 'Director:')?></strong> <?= $company->director ? LinkCreator::userLink($company->director) : Yii::t('yii', '(not set)') ?>
                            </p>
                            <p>
                                <strong><?=Yii::t('app', 'Capitalization:')?></strong> <span class="status-success"><?=MyHtmlHelper::aboutNumber($company->capitalization)?> <?=MyHtmlHelper::icon('money')?></span>
                            </p>
                            <p>
                                <strong><?=Yii::t('app', 'Balance')?></strong>
                                <?= MyHtmlHelper::moneyFormat($company->getBalance(0)) ?>
                            </p>
                            <?php if ($company->isGoverment): ?>
                            <p>
                                <?=Yii::t('app', 'This is a goverment company')?>
                            </p>
                            <?php endif ?>
                            <?php if ($company->isHalfGoverment): ?>
                            <p>
                                <?=Yii::t('app', 'This is a half goverment company')?>
                            </p>
                            <?php endif ?>
                            <?php if ($company->state && !$company->state->dateDeleted): ?>
                            <p>
                                <?=Yii::t('app', 'Company registered in state {0}', [LinkCreator::stateLink($company->state)])?>
                            </p>
                            <?php endif ?>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong><?=Yii::t('app', 'Shares issued:')?></strong> <?= MyHtmlHelper::formateNumberword($company->sharesIssued,'s') ?> <?=MyHtmlHelper::icon('money')?>
                            </p>
                            <p>
                                <strong><?=Yii::t('app', 'Share price:')?></strong> <?= MyHtmlHelper::moneyFormat($company->sharesPrice) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <span class="box-title">
                        <i class="fa fa-group"></i> <?=Yii::t('app', 'Shareholders')?>
                    </span>
                </div>
                <div class="box-content">    
                    <table class="table table-normal">
                        <thead>
                            <tr>
                                <td><?=Yii::t('app', 'Shareholder')?></td>
                                <td><?=Yii::t('app', 'Stake')?></td>
                            </tr>
                        </thead>
                        <?php foreach ($company->shares as $share): ?>
                            <tr>
                                <td><?= LinkCreator::link($share->master) ?></td>
                                <td>
                                    <?= MyHtmlHelper::formateNumberword($share->count, 's') ?> (<?= round(100*$share->count/$company->sharesIssued, 2) ?>%)
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <span class="box-title">
                        <i class="fa fa-legal"></i> <?=Yii::t('app', 'Licenses')?>
                    </span>
<!--                    <div class="box-tools pull-right">
                        <button class="btn btn-xs btn-success">
                            Получить лицензию
                        </button>
                    </div>-->
                </div>
                <div class="box-content">    
                    <table class="table table-normal">
                        <?php if (count($company->licenses)): ?>
                            <thead>
                                <tr>
                                    <td><?=Yii::t('app', 'License type')?></td>
                                    <td><?=Yii::t('app', 'State')?></td>
                                </tr>
                            </thead>
                            <?php foreach ($company->licenses as $license): ?>
                                <tr>
                                    <td><?= $license->proto->name ?></td>
                                    <td><?= LinkCreator::stateLink($license->state) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td><?=Yii::t('app', 'Company have no one license')?></td>
                            </tr>
                        <?php endif ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header">
                    <h4 class="box-title"><?=Yii::t('app', 'Active decisions')?></h4>
                </div>
                <div class="box-body">
                <?php if (count($company->decisionsActive)): ?>
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Initiator') ?></th>
                                <th><?= Yii::t('app', 'Date Created') ?></th>
                                <th><?= Yii::t('app', 'Decision content') ?></th>
                                <th><?= Yii::t('app', 'Votes') ?></th>
                                <th><?= Yii::t('app', 'Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($company->decisionsActive as $decision): ?>
                            <tr>
                                <td><?=$decision->initiator ? LinkCreator::link($decision->initiator) : Yii::t('app', 'Automatic decision')?></td>
                                <td>
                                    <span class="formatDate" data-unixtime="<?= $decision->dateCreated ?>" ><?= date('H:M d.m.Y', $decision->dateCreated) ?></span>
                                </td>
                                <td><?= $decision->render() ?></td>
                                <td class="text-center">
                                    <span class="badge bg-green">
                                        <?= round($decision->votesPlus/$company->sharesIssued,2) ?>%
                                    </span>
                                    &nbsp;/&nbsp;
                                    <span class="badge bg-red">
                                        <?= round($decision->votesMinus/$company->sharesIssued,2) ?>%
                                    </span>
                                    &nbsp;/&nbsp;
                                    <span class="badge bg-gray">
                                        <?= round($decision->votesAbstain/$company->sharesIssued,2) ?>%
                                    </span>
                                </td>
                                <td>
                                    <?= Html::a(Yii::t('app', 'Open decision info'), ['#!company/decision', 'id' => $decision->id, 'utr' => $shareholder->getUtr()], ['class' => 'btn btn-primary']) ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <?=Yii::t('app', 'No one active decisions')?>
                <?php endif ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid box-primary">
                <div class="box-header">
                    <h4 class="box-title"><?=Yii::t('app', 'Actions')?></h4>
                </div>
                <div class="box-body">
                    <button class="btn btn-lg btn-primary new-decision-btn"><?=Yii::t('app', 'New decision')?></button>
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
    
    $('.new-decision-btn').click(function(){
        $('#company-new-decision-list-form-modal .modal-dialog').removeClass('modal-lg');
        createAjaxModal(
            'company/new-decision-list-form',
            {id:<?=$company->id?>, utr:$('#select-using-utr').val()},
            '<?=Yii::t('app', 'New decision')?>',
            '<button id="new-decision-confirm-btn" onclick="$(\'#new-decision-form\').yiiActiveForm(\'submitForm\')" class="btn btn-primary new-decision-confirm-btn hide" ><?=Yii::t('app', 'Suggest new decision')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Close')?></button>'
        );
    });

</script>