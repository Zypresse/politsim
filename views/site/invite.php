<?php

    use yii\widgets\ActiveForm;

?>
<header>
    <div class="container">
        <h3>Загрузите картинку-инвайт, чтобы получить доступ к игре</h3>
    </div>
</header>
<div class="container">
    <div class="row">
        <div class="span6">
            <?php if ($model->getErrors()): ?>
            <ul style="color:red">
                <?php foreach ($model->getErrors()['imageFile'] as $error): ?>
                <li><?=$error?></li>
                <?php endforeach ?>
            </ul>
            <?php endif ?>
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

                <?= $form->field($model, 'imageFile')->fileInput() ?>

                <button class="btn btn-blue">Загрузить</button>

            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>