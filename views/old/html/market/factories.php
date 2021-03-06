<?php

use yii\grid\GridView,
    app\components\MyHtmlHelper,
    yii\helpers\Html,
    app\models\factories\Factory,
    app\models\factories\FactoryAuction;

/* @var $this yii\web\View */
/* @var $searchModel app\models\factories\FactoryAuctionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user app\models\User */

$unnps = [];
?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= $this->render('_menu', ['active' => 4]) ?>
            <div id="market-change-unnp" >
                <label for="#market-change-unnp-select" >Действовать от имени: </label>
                <select id="market-change-unnp-select" >
                    <option disabled value="<?= $user->unnp ?>">Физическое лицо</option>
                    <?php if ($user->post && $user->post->org && $user->post->org->isExecutive()): ?>
                        <option disabled value="<?= $user->post->unnp ?>"><?= $user->post->ministry_name ? $user->post->ministry_name : $user->post->name . ' (' . $user->post->org->name . ')' ?></option>
                    <?php endif ?>
                    <?php if ($user->isOrgLeader()): ?>
                        <option disabled value="<?= $user->post->org->unnp ?>"><?= $user->post->org->name ?></option>
                    <?php endif ?>
                    <?php if ($user->isStateLeader()): ?>
                        <option disabled value="<?= $user->state->unnp ?>"><?= $user->state->name ?></option>
                    <?php endif ?>
                    <?php /* if ($user->isRegionLeader()): ?>
                      <option disabled value="<?=$user->region->unnp?>"><?=$user->region->name?></option>
                    <?php endif */ ?>
                    <?php foreach ($user->holdings as $holding): $unnps[] = $holding->unnp; ?>
                        <option value="<?= $holding->unnp ?>"><?= $holding->name ?></option>
                    <?php endforeach ?>
                    <?php foreach ($user->factories as $factory): ?>
                        <option disabled value="<?= $factory->unnp ?>"><?= $factory->name ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <h3>Рынок недвижимости</h3>
            <?=
            GridView::widget([
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
                        'content' => function(FactoryAuction $model) {
                            return $model->factory->proto->name . " «" . MyHtmlHelper::a($model->factoryName, "load_page('factory-info',{'id':" . $model->factory_id . "})") . "»";
                        }
                    ],
                    [
                        'attribute' => 'holdingName',
                        'label' => 'Продавец',
                        'content' => function(FactoryAuction $model) {
                            return MyHtmlHelper::a($model->factory->holding->name, "load_page('holding-info',{'id':" . $model->factory->holding_id . "})");
                        }
                    ],
                    [
                        'attribute' => 'regionName',
                        'label' => 'Местоположение',
                        'content' => function(FactoryAuction $model) {
                            return $model->factory->region->getHtmlName();// MyHtmlHelper::a($model->factory->region->name, "show_region({$model->factory->region->id})") . ( $model->factory->region->state ? " (" . MyHtmlHelper::a($model->factory->region->state->short_name, "load_page('state-info',{'id':" . $model->factory->region->state_id . "})") . ")" : '');
                        }
                    ],
                    [
                        'attribute' => 'current_price',
                        'label' => 'Текущая цена',
                        'content' => function(FactoryAuction $model) {
                            return MyHtmlHelper::moneyFormat($model->current_price);
                        }
                    ],
                    [
                        'attribute' => 'end_price',
                        'label' => 'Стоп-цена',
                        'content' => function(FactoryAuction $model) {
                            return ($model->end_price) ? MyHtmlHelper::moneyFormat($model->end_price) : "<em>не установлена</em>";
                        }
                    ],
                    [
                        'attribute' => 'date_end',
                        'label' => 'Завершение',
                        'content' => function(FactoryAuction $model) {
                            return ($model->date_end > time()) ? MyHtmlHelper::timeFormatFuture($model->date_end) : "завершён";
                        }
                    ],
                    [
                        'label' => 'Действия',
                        'content' => function(FactoryAuction $model) {
                            return Html::button('Ставка', [
                                        'class' => 'btn btn-sm btn-primary btn-bet hide-on-unnp' . $model->factory->holding->unnp . ($model->lastBet ? ' hide-on-unnp' . $model->lastBet->holding->unnp : ''),
                                        'onclick' => 'load_modal("factory-auction-info",{"id":' . $model->id . ',"unnp":parseInt($("#market-change-unnp-select").val())},"factory_auction_info","factory_auction_info_body")'
                            ]);
                        }
                            ]
                        ],
                    ]);
                    ?>
        </div>


        <div style="display:none" class="modal fade" id="factory_auction_info" tabindex="-1" role="dialog" aria-labelledby="factory_auction_info_label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="factory_auction_info_label">Информация о аукционе</h3>
                    </div>
                    <div id="factory_auction_info_body" class="modal-body">
                        <p>Загрузка…</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
                        <!--<button class="btn btn-primary">Save changes</button>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    function updateButtons() {
        var unnp = parseInt($('#market-change-unnp-select').val());
        $('.btn-bet').removeAttr("disabled");
        $('.hide-on-unnp' + unnp).attr("disabled", "disabled");
    }

    $(function () {
        $('#market-factories-table th a').click(function () {
            $.get($(this).attr('href'), function (data) {
                $('#row1').html(data);
            });
            return false;
        });

        $('#market-change-unnp-select').change(updateButtons);
        updateButtons();
    });
</script>