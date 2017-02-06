<?php

use yii\widgets\ActiveForm,
    yii\helpers\Url,
    app\components\LinkCreator,
    yii\helpers\Html;

/* @var $this yii\base\View */
/* @var $user app\models\User */
/* @var $model app\models\TwitterProfile */

$form = new ActiveForm();

?>
<section class="content-header">
    <h1>
        <?= Yii::t('app', 'Social network') ?>
    </h1>
    <ol class="breadcrumb">
        <li><?= LinkCreator::userLink($user) ?></li>
        <li class="active"><?= Yii::t('app', 'Profile') ?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
        <?php
        $form->begin([
            'options' => [
                'id' => 'create-profile-form',
            ],
            'action' => Url::to(['twitter/create-profile-form']),
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'validationUrl' => Url::to(['twitter/create-profile-form'])
        ])
        ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-user"></i> <?= Yii::t('app', 'Select your nickname') ?></h3>
                </div>
                <div class="box-body">

                    <?=
                    $form->field($model, 'userId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()
                    ?>

                    <?= $form->field($model, 'nickname')->textInput() ?>

                </div>
                <div class="box-footer">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class'=>'btn btn-primary', 'onclick' => 'return false']) ?>
                </div>
            </div>
        <?php $form->end() ?>
        </div>
    </div>
</section>
<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>    
        
    $form = $('#create-profile-form');
    
    $form.yiiActiveForm('add', {
        'id': 'twitterprofile-nickname',
        'name': 'TwitterProfile[nickname]',
        'container': '.field-twitterprofile-nickname',
        'input': '#twitterprofile-nickname',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
        
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('twitter/create-profile', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
    });
</script>
