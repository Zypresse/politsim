<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\components\MyHtmlHelper;

/* @var $this \yii\web\View */
/* @var $building \app\models\economics\units\Building */
/* @var $user \app\models\User */

?>
<section class="content-header">
    <h1>
        <?= Html::encode($building->name) ?>
    </h1>
    <ol class="breadcrumb">
        <li><?= LinkCreator::link($building->master) ?></li>
        <li class="active"><?= Html::encode($building->name) ?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">            
            <div class="box">
                <div class="box-header">
                    <h1>
                        <?= Html::encode($building->name) ?>
                        <small>(<?= Html::encode($building->proto->name) ?>)</small>
                    </h1>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if ($building->dateDeleted): ?>
                                <div class="callout callout-danger">
                                    <h4><i class="icon fa fa-ban"></i> <?= Yii::t('app', 'Building destroyed!') ?></h4>
                                    <p><?= Yii::t('app', 'Building was destroyed at {0}', [MyHtmlHelper::timeAutoFormat($building->dateDeleted)]) ?></p>
                                </div>
                            <?php endif ?>
                            <?php if (is_null($building->dateBuilded)): ?>
                                <div class="callout callout-info">
                                    <h4><i class="icon fa fa-spinner"></i> <?= Yii::t('app', 'Building not constructed') ?></h4>
                                    <p><?= Yii::t('app', 'Construction started at {0}', [MyHtmlHelper::timeAutoFormat($building->dateCreated)]) ?></p>
                                </div>
                            <?php endif ?>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong><?= Yii::t('app', 'Building master:') ?></strong>
                                <?= LinkCreator::link($building->master) ?>
                            </p>
                            <p>
                                <strong><?= Yii::t('app', 'Manager:') ?></strong> <?= $building->manager ? LinkCreator::userLink($building->manager) : Yii::t('yii', '(not set)') ?>
                            </p>
                            <?php if ($building->tile): ?>
                                <p>
                                    <strong><?= Yii::t('app', 'Location:') ?></strong>
                                    <?= $building->tile->city ? LinkCreator::cityLink($building->tile->city) : LinkCreator::regionLink($building->tile->region) ?>
                                </p>
                            <?php endif ?>
                            <p>
                                <strong><?= Yii::t('app', 'Building size:') ?></strong>
                                <?= $building->size ?> <i class="fa fa-balance-scale"></i>
                            </p>
                            <p>
                                <strong><?= Yii::t('app', 'Building deterioration:') ?></strong>
                                <?= MyHtmlHelper::zeroOne2Stars($building->deterioration) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong><?= Yii::t('app', 'Status:') ?></strong>
                                <?= $building->status->name ?>
                            </p>
                            <p>
                                <strong><?= Yii::t('app', 'Current task:') ?></strong>
                                <?= $building->taskId ?>
                                <?= $building->taskSubId ?>
                                <?= $building->taskFactor ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($building->isUserController($user->id)): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid box-primary">
                    <div class="box-header">
                        <h4 class="box-title"><?= Yii::t('app', 'Actions') ?></h4>
                    </div>
                    <div class="box-body">
                        <button id="btn-control-vacancies" class="btn btn-success"><i class="fa fa-money"></i> <?= Yii::t('app', 'Control vacancies') ?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
</section>
<?php if ($building->isUserController($user->id)): ?>
<script type="text/javascript">
    
    var btnCloseModal = '<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Close')?></button>';

    $('#btn-control-vacancies').click(function(){
        createAjaxModal(
            'building/vacancies',
            {id:<?=$building->id?>},
            '<?= Yii::t('app', 'Vacancies') ?>',
            btnCloseModal
        );
    });
    
</script>
<?php endif ?>
