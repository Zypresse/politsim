<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\models\map\CitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Регионы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-index">

    <p>
        <?= Html::a('Создать регион', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'nameShort',
            [
                'attribute' => 'cityId',
                'value' => function($model) {
                    return $model->capital ? $model->capital->name : null;
                },
                'format' => 'raw',
            ],
            //'flag',
            //'anthem',
            'population',
            'area',
            //'usersCount',
            //'usersFame',
            [
                'attribute' => 'tilesCount',
                'value' => function ($model) {
                    return $model->tilesCount ? "<span class='text-green'>{$model->tilesCount}</span>" : "<span class='text-red'>НЕТ ТАЙЛОВ</span>";
                },
                'format' => 'raw',
            ],
            'utr',

            [
                'class' => ActionColumn::class,
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
</div>