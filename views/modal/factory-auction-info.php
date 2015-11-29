<?php
/* @var $auction app\models\factories\FactoryAuction */
/* @var $master app\components\NalogPayer */

use app\components\MyHtmlHelper;

$minBet = max([$auction->start_price,round($auction->current_price*1.1)]);
if ($minBet === floatval($auction->current_price)) {
    $minBet++;
}
$maxBet = min([$auction->end_price ? $auction->end_price : INF,$master->getBalance()]);

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
<? if (count($auction->bets)): ?>
<ul>
    <? foreach ($auction->bets as $bet): ?>
        <li><?=MyHtmlHelper::moneyFormat($bet->bet)?>  <?=$bet->holding->getHtmlName()?></li>
    <? endforeach ?>
</ul>
<? else: ?>
<p>Пока не сделано ни одной ставки</p>
<? endif ?>

<? if ($auction->lastBet && $auction->lastBet->holding_id === $master->id): ?>
<p style="color:green">Ваша ставка последняя за этот лот</p>
<? else: ?>
<h5>Действия:</h5>
<div class="row">
    <div class="span4">
        <table class="table">
            <tr>
                <td style="text-align: right" >
                    <input type="number" id="bet_size" class="input-money" value="<?=$minBet?>" >
                    <?=MyHtmlHelper::icon('money')?>
                </td>
                <td>
                    <button class="btn btn-default" id="make_bet" >
                      Сделать ставку
                    </button>
                </td>
            </tr>
            <? if ($auction->end_price): ?>
                <tr>
                    <td style="text-align: right" >
                        <?=MyHtmlHelper::moneyFormat($auction->end_price)?>
                    </td>
                    <td>
                        <button class="btn btn-gold" id="make_end_bet" title="Купить лот не дожидаясь конца аукциона, заплатив стоп-цену" >
                            Выкупить лот
                        </button>
                    </td>
                </tr>
            <? endif ?>
        </table>
    </div>
</div>

<script type="text/javascript">
    
    var updateBetSize = function() {
        var size = parseFloat($('#bet_size').val());
        if (size < <?=$minBet?>) {
            $('#bet_size').val(<?=$minBet?>);
        }
        if (size > <?=$maxBet?>) {
            $('#bet_size').val(<?=$maxBet?>);
        }
    }
    
    $(function(){
        $('#bet_size').keyup(updateBetSize);
        $('#bet_size').change(updateBetSize);
        
        $('#make_end_bet').click(function(){
            if (<?=intval($auction->end_price)?> > <?=$master->getBalance()?>) {
                alert('У <?=$master->name?> недостаточно денег на счету!');
            } else {
                json_request('factory-market-bet',{'unnp':<?=$master->unnp?>, 'auction_id':<?=$auction->id?>, 'bet_size':<?=intval($auction->end_price)?>});
            }
        })

        $('#make_bet').click(function(){
            var size = parseFloat($('#bet_size').val());

            if (size > <?=$master->getBalance()?>) {
                alert('У <?=$master->name?> недостаточно денег на счету!');
            } else {
                json_request('factory-market-bet',{'unnp':<?=$master->unnp?>, 'auction_id':<?=$auction->id?>, 'bet_size':size});
            }
        })
    })
    
</script>
<? endif ?>