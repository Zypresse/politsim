<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\map\City */

$this->title = 'Создание региона';
$this->params['breadcrumbs'][] = ['label' => 'Регионы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
