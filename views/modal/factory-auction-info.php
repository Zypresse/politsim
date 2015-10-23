<?php
/* @var $auction app\models\factories\FactoryAuction */
/* @var $master app\components\NalogPayer */

use app\components\MyHtmlHelper;

?>
<h4><?=$auction->factory->getHtmlName()?></h4>
<p>Владелец: <?=$auction->factory->holding->getHtmlName()?></p>
<p>Местоположение: <?=$auction->factory->region->getHtmlName()?></p>

<h5>Условия аукциона:</h5>
<p>Стартовая цена: <?=MyHtmlHelper::moneyFormat($auction->start_price)?></p>
<? if ($auction->end_price): ?>
    <p>Стоп-цена: <?=MyHtmlHelper::moneyFormat($auction->end_price)?></p>
<? endif ?>

<h5>Ставки:</h5>
<p>Пока не сделано ни одной ставки</p>

<h5>Действия:</h5>
<table class="table">
<tr>
    <td>
        <input type="number" id="bet_size" class="input-money" value="<?=max([$auction->start_price,round($auction->current_price*1.1)])?>" >
        <?=MyHtmlHelper::icon('money')?>
    </td>
    <td>
        <button class="btn btn-default btn-sm" id="make_bet" >
          Сделать ставку
        </button>
    </td>
</tr>
<? if ($auction->end_price): ?>
    <tr>
        <td>
            <?=MyHtmlHelper::moneyFormat($auction->end_price)?>
        </td>
        <td>
            <button class="btn btn-success" id="make_end_bet" title="Купить лот не дожидаясь конца аукциона, заплатив стоп-цену" >
                Выкупить лот
            </button>
        </td>
    </tr>
<? endif ?>