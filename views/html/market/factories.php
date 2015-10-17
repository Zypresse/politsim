<?php

use yii\grid\GridView,
    app\components\MyHtmlHelper,
    yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InviteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user app\models\User */

?>
<?=$this->render('_menu',['active' => 4])?>
<div id="market-change-unnp" >
    <label for="#market-change-unnp-select" >Действовать от имени: </label>
    <select id="market-change-unnp-select" >
        <option value="<?=$user->unnp?>">Физическое лицо</option>
        <? if ($user->post && $user->post->org && $user->post->org->isExecutive()): ?>
        <option value="<?=$user->post->unnp?>"><?=$user->post->ministry_name?$user->post->ministry_name:$user->post->name.' ('.$user->post->org->name.')'?></option>
        <? endif ?>
        <? if ($user->isOrgLeader()): ?>
        <option value="<?=$user->post->org->unnp?>"><?=$user->post->org->name?></option>
        <? endif ?>
        <? if ($user->isStateLeader()): ?>
        <option value="<?=$user->state->unnp?>"><?=$user->state->name?></option>
        <? endif ?>
        <?/* if ($user->isRegionLeader()): ?>
        <option value="<?=$user->region->unnp?>"><?=$user->region->name?></option>
        <? endif */?>
        
    </select>
</div>
<h3>Рынок недвижимости</h3>
<div class="span12" style="margin-top: 10px" >
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered',
            'id' => 'market-factories-table'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'factoryName',
                'label' => 'Предприятие',
                'content' => function($model) {
                    return $model->factory->proto->name . " «" . MyHtmlHelper::a($model->factoryName,"load_page('factory-info',{'id':".$model->factory_id."})"). "»";
                }
            ],
            [
                'attribute' => 'holdingName',
                'label' => 'Продавец',
                'content' => function($model) {
                    return MyHtmlHelper::a($model->factory->holding->name,"load_page('holding-info',{'id':".$model->factory->holding_id."})");
                }
            ],
            [
                'attribute' => 'regionName',
                'label' => 'Местоположение',
                'content' => function($model) {
                    return $model->factory->region->name . ( $model->factory->region->state ? " (" . MyHtmlHelper::a($model->factory->region->state->short_name,"load_page('state-info',{'id':".$model->factory->region->state_id."})") . ")" : '');
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
                    return ($model->end_price) ? MyHtmlHelper::moneyFormat($model->end_price) : "<em>не установлена</em>";
                }
            ],
            [
                'attribute' => 'date_end',
                'label' => 'Завершение',
                'content' => function($model) {
                    return MyHtmlHelper::timeFormatFuture($model->date_end);
                }
            ],
            [
                'label' => 'Действия',
                'content' => function($model) {
                    return Html::button('Ставка', [
                        'class' => 'btn btn-small btn-primary',
                        'onclick' => ''
                    ]);
                }
            ]

        ],
    ]); ?>
</div>

<script type="text/javascript">
    $(function() {
        $('#market-factories-table th a').click(function(){
            $.get($(this).attr('href'), function(data){
                $('#row1').html(data);
            })
            return false;
        })
    })
</script>