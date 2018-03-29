<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\map\City */

$this->title = 'Редактирование города: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Города', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="city-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
