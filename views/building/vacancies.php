<?php

use yii\helpers\Html,
    yii\helpers\ArrayHelper,
    app\components\MyHtmlHelper,
    app\models\population\PopClass;

/* @var $this \yii\web\View */
/* @var $building \app\models\economics\units\Building */
/* @var $user \app\models\User */

?>
<?php if (count($building->vacancies)): ?>
<div id="vacancies-block">
    <?php foreach ($building->vacancies as $vacancy): ?>
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $vacancy->popClass->name ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-primary btn-xs btn-edit-vacancy" data-vacancy-id="<?= $vacancy->id ?>" ><i class="fa fa-edit"></i> <?= Yii::t('app', 'Edit vacancy') ?></button>
                    <button class="btn btn-danger btn-xs btn-delete-vacancy" data-vacancy-id="<?= $vacancy->id ?>" ><i class="fa fa-times"></i> <?= Yii::t('app', 'Delete vacancy') ?></button>
                </div>
            </div>
            <div class="box-body">
                <div class="col-md-6 col-sm-12 form-group">
                    <label><?= Yii::t('app', 'Count:') ?> </label>
                    <?= number_format($vacancy->countFree, 0, '', ' ') ?> / 
                    <?= MyHtmlHelper::formateNumberword($vacancy->countAll, 'h') ?>
                </div>
                <div class="col-md-6 col-sm-12 form-group">
                    <label><?= Yii::t('app', 'Wage:') ?> </label>
                    <?= MyHtmlHelper::moneyFormat($vacancy->wage, 2) ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
<?php else: ?>
    <div id="vacancies-block"></div>
    <p><?= Yii::t('app', 'No vacancies yet') ?></p>
<?php endif ?>
<div class="form-group">
    <button id="btn-add-vacancy" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Add vacancy') ?></button>
</div>
<div class="help-block text-light-blue">
    <p><i class="fa fa-exclamation-triangle"></i> <?= Yii::t('app', 'Building needs next workers:') ?></p>
    <ul>
        <?php foreach ($building->proto->workPopsPacks as $pack): ?>
            <li><strong><?= $pack->popClass->name ?></strong> — <?= MyHtmlHelper::formateNumberword($pack->count * $building->size, 'h') ?></li>
        <?php endforeach ?>
    </ul>
    <p><?= Yii::t('app', 'But you can set any vacancies you want') ?></p>
</div>    
<script type="text/javascript">
    
    $('#btn-add-vacancy').click(function(){
        createAjaxModal(
            'building/vacancy-create-form',
            {id:<?=$building->id?>},
            '<?= Yii::t('app', 'Create vacancy') ?>',
            '<button onclick="$(\'#new-vacancy-form\').yiiActiveForm(\'submitForm\')" class="btn btn-primary"><?= Yii::t('app', 'Save') ?></button>'+btnCloseModal,
            'building-vacancies-modal'
        );
    });
    
    $('.btn-delete-vacancy').click(function(){
        json_post_request('building/vacancy-delete',{id:<?=$building->id?>,vacancyId:$(this).data('vacancyId')});
    });
    
    $('.btn-edit-vacancy').click(function(){
        createAjaxModal(
            'building/vacancy-edit-form',
            {id:<?=$building->id?>,vacancyId:$(this).data('vacancyId')},
            '<?= Yii::t('app', 'Edit vacancy') ?>',
            '<button onclick="$(\'#edit-vacancy-form\').yiiActiveForm(\'submitForm\')" class="btn btn-primary"><?= Yii::t('app', 'Save') ?></button>'+btnCloseModal,
            'building-vacancies-modal'
        );
    });
    
</script>
