<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\map\City */

$this->title = 'Создание города';
$this->params['breadcrumbs'][] = ['label' => 'Города', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
