<?php

use yii\grid\GridView,
    app\components\MyHtmlHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InviteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<h3>Рынок недвижимости</h3>
<?=$this->render('_menu',['active' => 4])?>
<div class="span12" style="margin-top: 10px" >
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'factory_id',
                'label' => 'Предприятие',
                'content' => function($model) {
                    return MyHtmlHelper::a($model->factory->type->name . " «" . $model->factory->name . "»","load_page('factory-info',{'id':".$model->factory_id."})");
                }
            ],
            [
                'attribute' => 'current_price',
                'label' => 'Текущая цена',
                'content' => function($model) {
                    return MyHtmlHelper::moneyFormat($model->current_price);
                }
            ], 
            [
                'attribute' => 'end_price',
                'label' => 'Стоп-цена',
                'content' => function($model) {
                    return MyHtmlHelper::moneyFormat($model->end_price);
                }
            ],
            [
                'attribute' => 'date_end',
                'label' => 'Завершение',
                'content' => function($model) {
                    return MyHtmlHelper::timeFormatFuture($model->date_end);
                }
            ],

        ],
    ]); ?>
</div>