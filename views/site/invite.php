<?php

    use yii\widgets\ActiveForm;

?>

<div class="content" style="background-color: white">
    <div class="col-md-6">
        <h3>Загрузите картинку-инвайт, чтобы получить доступ к игре</h3>
        <?php if ($model->getErrors()): ?>
        <ul style="color:red">
            <?php foreach ($model->getErrors()['imageFile'] as $error): ?>
            <li><?=$error?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

            <?= $form->field($model, 'imageFile')->fileInput() ?>

            <button class="btn btn-primary">Загрузить</button>

        <?php ActiveForm::end() ?>
    </div>
</div>