<?php

/**
 * Страница управления бизнесом
 * 
 * */
use app\components\MyHtmlHelper;
use yii\helpers\Html;

/* @var $user app\models\User */
?>
<h1>Управление бизнесом</h1>
<h3>Ваши акции: <button class="btn btn-mini" id="stock_list_button">скрыть/показать</button></h3>
<table class="table" id="stocks_list" style="display: none">
    <thead>
        <tr>
            <th>Фирма</th>
            <th>Количество акций</th>
            <th>Примерная рыночная стоимость</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
<? if (count($user->stocks)) {
    foreach ($user->stocks as $stock): if ($stock->holding): ?>
                <tr>
                    <td><a href="#" onclick="load_page('holding-info', {'id':<?= $stock->holding_id ?>})"><?= $stock->holding->name ?></a></td>
                    <td><?= MyHtmlHelper::formateNumberword($stock->count, "акций", "акция", "акции") ?> (<?= round($stock->getPercents(), 2) ?>%)</td>
                    <td>≈ <?= number_format($stock->getCost(), 0, '', ' ') ?> <?= MyHtmlHelper::icon('money') ?></td>
                    <td><?= Html::a("Управление", "#", ['class' => 'btn btn-primary', 'onclick' => 'load_page("holding-info",{"id":' . $stock->holding_id . '})']) ?></td>
                </tr>
    <? endif; endforeach;
} else { ?>
            <tr><td colspan="4">Не владеет акциями</td></tr>
        <? } ?>
    </tbody>
</table>

<h3>Управление: <button class="btn btn-mini" id="managefactories_list_button">скрыть/показать</button></h3>
<table id="managefactories_list" class="table" style="display: none">
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
        <? if (count($user->factories)) {
            foreach ($user->factories as $factory) { ?>
                <tr>
                    <td><?= MyHtmlHelper::a($factory->name, "load_page('factory-info',{'id':{$factory->id}})") ?></td>
                    <td><?= MyHtmlHelper::a($factory->holding->name, "load_page('holding-info',{'id':{$factory->holding_id}})") ?></td>
                    <td><?= $factory->region->name ?></td>
                    <td><?= $factory->statusName ?></td>
                    <td><?= Html::a("Управление", "#", ['class' => 'btn btn-primary', 'onclick' => 'load_page("factory-info",{"id":' . $factory->id . '})']) ?></td>
                </tr>
            <? }
        } else { ?>
            <tr><td colspan="5">Не управляет ни одним обьектом</td></tr>
<? } ?>
    </tbody>
</table>
<?php 
    $inHomeland = ($user->region && $user->region->state_id === $user->state_id);
?>
<? if ($user->region && $user->region->state && $user->state_id) { ?>
    <? if ($inHomeland) : ?>
        <? if ($user->state->allow_register_holdings): ?>
            <p><button class="btn btn-success btn-small" onclick="$('#create_holding_dialog').modal()">Создать акционерное общество</button></p>
        <? else: ?>
            <p>Регистрировать компании в вашей стране запрещено.</p>
        <? endif ?>
    <? else: ?>
        <? if ($user->region->state->allow_register_holdings_noncitizens): ?>
            <p><button class="btn btn-success btn-small" onclick="$('#create_holding_dialog').modal()">Создать акционерное общество</button></p>
        <? else: ?>
            <p>Иностранцам запрещено регистрировать компании в этой стране.</p>
        <? endif ?>
    <? endif ?>
    <div style="display:none" class="modal" id="create_holding_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Создать акционерное общество</h3>
        </div><form class="well form-horizontal">
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
                        <? if ($inHomeland) : ?>
                            Начальная капитализация не должна быть меньше чем <?=MyHtmlHelper::moneyFormat($user->region->state->register_holdings_mincap)?>
                            <? if ($user->region->state->register_holdings_maxcap > 0): ?>
                            <br> Начальная капитализация не должна быть больше чем <?=MyHtmlHelper::moneyFormat($user->region->state->register_holdings_maxcap)?>
                            <? endif ?>
                        <? else: ?>
                            Начальная капитализация не должна быть меньше чем <?=MyHtmlHelper::moneyFormat($user->region->state->register_holdings_noncitizens_mincap)?>
                            <? if ($user->region->state->register_holdings_noncitizens_maxcap > 0): ?>
                            <br> Начальная капитализация не должна быть больше чем <?=MyHtmlHelper::moneyFormat($user->region->state->register_holdings_noncitizens_maxcap)?>
                            <? endif ?>
                        <? endif ?>
                    </p>
                </div>
            <? if ($inHomeland) : ?>
                <p>Гос. пошлина: <?= MyHtmlHelper::moneyFormat($user->region->state->register_holdings_cost) ?></p>
            <? else: ?>
                <p>Гос. пошлина для иностранцев: <?= MyHtmlHelper::moneyFormat($user->region->state->register_holdings_noncitizens_cost) ?></p>
            <? endif ?>
                <p>Всего вам нужно заплатить: <span id="new_holding_sum_cost"></span> <?=MyHtmlHelper::icon('money')?></p>
            </div>
            <div class="modal-footer">
                <button type="submit" onclick="if ($('#holding_name').val() && $('#new_holding_capitalisation').val()) json_request('create-holding', {'name': $('#holding_name').val(),'capital':$('#new_holding_capitalisation').val()})" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Создать</button>
                <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div></form>
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
<? } ?>

<script type="text/javascript">        
    $(function () {
        $('#stock_list_button').toggle(function () {
            $('#stocks_list').slideDown();
        }, function () {
            $('#stocks_list').slideUp();
        });
        $('#managefactories_list_button').toggle(function () {
            $('#managefactories_list').slideDown();
        }, function () {
            $('#managefactories_list').slideUp();
        });  
    });
</script>