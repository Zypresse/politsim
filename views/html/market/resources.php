<?php

/* @var $this yii\web\View */
/* @var $prototypes app\models\resources\proto\ResourceProto[] */
/* @var $user app\models\User */

$unnps = [];
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?= $this->render('_menu', ['active' => 2]) ?>
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
                    <?php foreach ($user->holdings as $holding): ?>
                        <option disabled value="<?= $holding->unnp ?>"><?= $holding->name ?></option>
                    <?php endforeach ?>
                    <?php foreach ($user->factories as $factory): $unnps[] = $factory->unnp; ?>
                        <option value="<?= $factory->unnp ?>"><?= $factory->name ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <h3>Рынок ресурсов</h3>
            <?php foreach ($prototypes as $i => $proto): ?>
                <button class="btn btn-xs btn-default <?php if ($i === 0) { ?>btn-lightblue<?php } ?> resources_market_btn" data-id="<?= $proto->id ?>" ><?= $proto->getHtmlName() ?></button>
            <?php endforeach ?>
        </div>
    </div>
    <div class="row" style="margin-top:10px">
        <div class="col-md-12" id="resources-market-body">

        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('.resources_market_btn').click(function () {
            $('.resources_market_btn').removeClass('btn-lightblue');
            $(this).addClass('btn-lightblue');
            load_resources_market($(this).data('id'));
        });

        $('#market-change-unnp-select').change(function () {
            var id = $('.resources_market_btn.btn-lightblue').data('id');
            load_resources_market(id);
        });
        load_resources_market(1);
    });

    function load_resources_market(id) {
        $('#resources-market-body').empty();
        get_html('market-resources', {'resource_proto_id': id, 'unnp': $('#market-change-unnp-select').val()}, function (data) {
            $('#resources-market-body').html(data);
        });
    }
</script>