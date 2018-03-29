<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\map\City */

$this->title = 'Редактирование региона: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Регионы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="city-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
