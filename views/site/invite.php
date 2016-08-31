<?php

use yii\bootstrap\ActiveForm,
    app\components\MyHtmlHelper;

/* @var $this yii\web\View */
/* @var $model app\models\InviteForm */

$this->title = Yii::t('app', 'Activate account | Political Simulator');

?>
<header id="top" class="header">
    <div class="text-vertical-center container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 col-sm-12">
                <h1>Political Simulator</h1>
                <h3><?=Yii::t('app','Upload your invite picture')?></h3>
                <br>
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'class' => "form-group"]) ?>

                    <?= $form->field($model, 'imageFile')->fileInput(['style' => 'display: inline']) ?>

                    <button class="btn btn-lg btn-primary"><?=Yii::t('app', 'Upload')?></button>

                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</header>