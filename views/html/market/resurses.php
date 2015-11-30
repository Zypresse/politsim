<?php

use yii\grid\GridView,
    app\components\MyHtmlHelper,
    yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $prototypes app\models\resurses\proto\ResurseProto[] */
/* @var $user app\models\User */

$unnps = [$user->unnp];
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?= $this->render('_menu', ['active' => 2]) ?>
            <div id="market-change-unnp" >
                <label for="#market-change-unnp-select" >Действовать от имени: </label>
                <select id="market-change-unnp-select" >
                    <option disabled value="<?= $user->unnp ?>">Физическое лицо</option>
                    <? if ($user->post && $user->post->org && $user->post->org->isExecutive()): $unnps[] = $user->post->unnp; ?>
                        <option disabled value="<?= $user->post->unnp ?>"><?= $user->post->ministry_name ? $user->post->ministry_name : $user->post->name . ' (' . $user->post->org->name . ')' ?></option>
                    <? endif ?>
                    <? if ($user->isOrgLeader()): $unnps[] = $user->post->org->unnp; ?>
                        <option disabled value="<?= $user->post->org->unnp ?>"><?= $user->post->org->name ?></option>
                    <? endif ?>
                    <? if ($user->isStateLeader()): $unnps[] = $user->state->unnp; ?>
                        <option disabled value="<?= $user->state->unnp ?>"><?= $user->state->name ?></option>
                    <? endif ?>
                    <? /* if ($user->isRegionLeader()): ?>
                      <option disabled value="<?=$user->region->unnp?>"><?=$user->region->name?></option>
                      <? endif */ ?>
                    <? foreach ($user->holdings as $holding): $unnps[] = $holding->unnp; ?>
                        <option disabled value="<?= $holding->unnp ?>"><?= $holding->name ?></option>
                    <? endforeach ?>
                    <? foreach ($user->factories as $factory): $unnps[] = $factory->unnp; ?>
                        <option value="<?= $factory->unnp ?>"><?= $factory->name ?></option>
                    <? endforeach ?>
                </select>
            </div>
            <h3>Рынок ресурсов</h3>
            <? foreach ($prototypes as $i => $proto): ?>
                <button class="btn btn-xs btn-default <? if ($i === 0) { ?>btn-lightblue<? } ?> resurses_market_btn" data-id="<?= $proto->id ?>" ><?= $proto->getHtmlName() ?></button>
            <? endforeach ?>
        </div>
    </div>
    <div class="row" style="margin-top:10px">
        <div class="col-md-12" id="resurses-market-body">

        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('.resurses_market_btn').click(function () {
            $('.resurses_market_btn').removeClass('btn-lightblue');
            $(this).addClass('btn-lightblue');
            load_resurses_market($(this).data('id'));
        })

        $('#market-change-unnp-select').change(function () {
            var id = $('.resurses_market_btn.btn-lightblue').data('id');
            load_resurses_market(id);
        });
        load_resurses_market(1);
    })

    function load_resurses_market(id) {
        $('#resurses-market-body').empty();
        get_html('market-resurses', {'resurse_proto_id': id, 'unnp': $('#market-change-unnp-select').val()}, function (data) {
            $('#resurses-market-body').html(data);
        })
    }
</script>