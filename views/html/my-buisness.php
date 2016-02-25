<?php

/**
 * Страница управления бизнесом
 * 
 * */
use app\components\MyHtmlHelper;
use yii\helpers\Html;

/* @var $user app\models\User */
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
<h1>Управление бизнесом</h1>
<h3>Ваши акции: <button class="btn btn-sm btn-default" id="stock_list_button">Скрыть</button></h3>
<table class="table" id="stocks_list" >
    <thead>
        <tr>
            <th>Фирма</th>
            <th>Количество акций</th>
            <th>Примерная рыночная стоимость</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
<?php if (count($user->stocks)) {
    foreach ($user->stocks as $stock): if ($stock->holding): ?>
                <tr>
                    <td><a href="#" onclick="load_page('holding-info', {'id':<?= $stock->holding_id ?>})"><?= $stock->holding->name ?></a></td>
                    <td><?= MyHtmlHelper::formateNumberword($stock->count, "акций", "акция", "акции") ?> (<?= round($stock->getPercents(), 2) ?>%)</td>
                    <td>≈ <?= number_format($stock->getCost(), 0, '', ' ') ?> <?= MyHtmlHelper::icon('money') ?></td>
                    <td><?= Html::a("Управление", "#", ['class' => 'btn btn-primary', 'onclick' => 'load_page("holding-info",{"id":' . $stock->holding_id . '})']) ?></td>
                </tr>
    <?php endif; endforeach;
} else { ?>
            <tr><td colspan="4">Не владеет акциями</td></tr>
        <?php } ?>
    </tbody>
</table>

<h3>Управление: <button class="btn btn-sm btn-default" id="managefactories_list_button">Скрыть</button></h3>
<table id="managefactories_list" class="table">
    <thead>
        <tr>
            <th>Обьект</th>
            <th>Фирма</th>
            <th>Регион</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($user->factories)) {
            foreach ($user->factories as $factory) { ?>
                <tr>
                    <td><?= MyHtmlHelper::a($factory->name, "load_page('factory-info',{'id':{$factory->id}})") ?></td>
                    <td><?= MyHtmlHelper::a($factory->holding->name, "load_page('holding-info',{'id':{$factory->holding_id}})") ?></td>
                    <td><?= $factory->region->name ?></td>
                    <td><?= $factory->statusName ?></td>
                    <td><?= Html::a("Управление", "#", ['class' => 'btn btn-primary', 'onclick' => 'load_page("factory-info",{"id":' . $factory->id . '})']) ?></td>
                </tr>
            <?php }
        } else { ?>
            <tr><td colspan="5">Не управляет ни одним обьектом</td></tr>
<?php } ?>
    </tbody>
</table>
<?php 
    $inHomeland = ($user->region && $user->region->state_id === $user->state_id);
?>
<?php if ($user->region && $user->region->state && $user->state_id) { ?>
    <?php if ($inHomeland) : ?>
        <?php if ($user->state->allow_register_holdings): ?>
            <p><button class="btn btn-green btn-sm" onclick="$('#create_holding_dialog').modal()">Создать акционерное общество</button></p>
        <?php else: ?>
            <p>Регистрировать компании в вашей стране запрещено.</p>
        <?php endif ?>
    <?php else: ?>
        <?php if ($user->region->state->allow_register_holdings_noncitizens): ?>
            <p><button class="btn btn-green btn-sm" onclick="$('#create_holding_dialog').modal()">Создать акционерное общество</button></p>
        <?php else: ?>
            <p>Иностранцам запрещено регистрировать компании в этой стране.</p>
        <?php endif ?>
    <?php endif ?>
    <div style="display:none" class="modal fade" id="create_holding_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
                    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Создать акционерное общество</h3>
        </div>
            <div id="create_holding_dialog_body" class="modal-body">
                <p>Вы сейчас находитесь в регионе <?= $user->region->name ?>, компания будет зарегистрирована в государстве <?= $user->region->state->name ?></p>
                <div class="control-group">
                    <label class="control-label" for="#holding_name">Название</label>
                    <div class="controls">
                        <input type="text" id="holding_name" placeholder="ОАО «Рога и копыта»">
                    </div>
                    <label class="control-label" for="#new_holding_capitalisation">Стартовая капитализация</label>
                    <div class="controls">
                        <input type="text" id="new_holding_capitalisation" value="<?=($inHomeland ? $user->region->state->register_holdings_mincap : $user->region->state->register_holdings_noncitizens_mincap)?>"> <?=MyHtmlHelper::icon('money')?>
                    </div>
                    <p class="help-inline">
                        <?php if ($inHomeland) : ?>
                            Начальная капитализация не должна быть меньше чем <?=MyHtmlHelper::moneyFormat($user->region->state->register_holdings_mincap)?>
                            <?php if ($user->region->state->register_holdings_maxcap > 0): ?>
                            <br> Начальная капитализация не должна быть больше чем <?=MyHtmlHelper::moneyFormat($user->region->state->register_holdings_maxcap)?>
                            <?php endif ?>
                        <?php else: ?>
                            Начальная капитализация не должна быть меньше чем <?=MyHtmlHelper::moneyFormat($user->region->state->register_holdings_noncitizens_mincap)?>
                            <?php if ($user->region->state->register_holdings_noncitizens_maxcap > 0): ?>
                            <br> Начальная капитализация не должна быть больше чем <?=MyHtmlHelper::moneyFormat($user->region->state->register_holdings_noncitizens_maxcap)?>
                            <?php endif ?>
                        <?php endif ?>
                    </p>
                </div>
            <?php if ($inHomeland) : ?>
                <p>Гос. пошлина: <?= MyHtmlHelper::moneyFormat($user->region->state->register_holdings_cost) ?></p>
            <?php else: ?>
                <p>Гос. пошлина для иностранцев: <?= MyHtmlHelper::moneyFormat($user->region->state->register_holdings_noncitizens_cost) ?></p>
            <?php endif ?>
                <p>Всего вам нужно заплатить: <span id="new_holding_sum_cost"></span> <?=MyHtmlHelper::icon('money')?></p>
            </div>
            <div class="modal-footer">
                <button type="submit" onclick="if ($('#holding_name').val() && $('#new_holding_capitalisation').val()) json_request('create-holding', {'name': $('#holding_name').val(),'capital':$('#new_holding_capitalisation').val()})" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Создать</button>
                <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
                    </div></div>
    </div>
    </div>
</div>
<script type="text/javascript">
    
    var updateNewHoldingCost = function(){
        var capital = parseInt($('#new_holding_capitalisation').val());
        var minCap = <?=$inHomeland?$user->region->state->register_holdings_mincap:$user->region->state->register_holdings_noncitizens_mincap?>;
        var maxCap = <?=$inHomeland?$user->region->state->register_holdings_maxcap:$user->region->state->register_holdings_noncitizens_maxcap?>;
        if (capital < minCap) {
            capital = minCap;
            $('#new_holding_capitalisation').val(capital);
        }
        if (maxCap > 0 && capital > maxCap) {
            capital = maxCap;
            $('#new_holding_capitalisation').val(capital);
        }
        var sumCost = <?=$inHomeland?$user->region->state->register_holdings_cost:$user->region->state->register_holdings_noncitizens_cost?> + capital;
        $('#new_holding_sum_cost').text(number_format(sumCost,0,'.',' '));
    }

    $(function(){
        $('#new_holding_capitalisation').keyup(updateNewHoldingCost);
        updateNewHoldingCost();
    });
</script>
<?php } ?>

<script type="text/javascript">        
    $(function () {
        $('#stock_list_button').click(function () {
            if ($(this).val() === 'Показать') {
                $(this).val('Скрыть');
                $('#stocks_list').slideDown();
            } else {
                $(this).val('Показать');
                $('#stocks_list').slideUp();
            }
        });
        $('#managefactories_list_button').click(function () {
            if ($(this).val() === 'Показать') {
                $(this).val('Скрыть');
                $('#managefactories_list').slideDown();
            } else {
                $(this).val('Показать');
                $('#managefactories_list').slideUp();
            }
        });  
    });
</script>