<?php

/* @var $resCost app\models\resurses\ResurseCost */
/* @var $viewer app\models\factories\Factory */

use app\components\MyHtmlHelper,
    app\models\Region;

$transferCost = round($resCost->resurse->place->object->region->calcDist($viewer->region)*Region::TRANSFER_COST);

?>
<input type="hidden" id="resurse_selling_dealing_cost_id" value="<?=$resCost->id?>">
<p><?=$viewer->getHtmlName()?> (<?=$viewer->holding->getHtmlName()?>) собирается приобрести у <?=$resCost->resurse->place->object->getHtmlName()?> (<?=$resCost->resurse->place->object->holding->getHtmlName()?>)</p>
<p><input id="resurse_selling_dealing_count" type="number" value="<?=$resCost->resurse->count?>" > <?=$resCost->resurse->proto->icon?></p>
<p>по цене в <?=MyHtmlHelper::moneyFormat($resCost->cost)?> за единицу.</p>
<p>Доставка из <?=$resCost->resurse->place->object->region->getHtmlName()?> в <?=$viewer->holding->region->getHtmlName()?> обойдётся в <?=number_format($transferCost,0,'',' ')?> <?=MyHtmlHelper::icon('money')?></p>
<p>Итого: <span id="resurse_selling_dealing_sum"></span> <?=MyHtmlHelper::icon('money')?></p>
<script type="text/javascript">

    function updateResSelDealSum() {
        var count = parseFloat($('#resurse_selling_dealing_count').val());
        if (count <= 0 || isNaN(count)) {
            count = 1;
        }
        if (count > <?=$resCost->resurse->count?>) {
            count = <?=$resCost->resurse->count?>;
        }
        $('#resurse_selling_dealing_count').val(count);
        
        var sum = <?=$resCost->cost?> * count + <?=$transferCost?>;
        $('#resurse_selling_dealing_sum').text(number_format(sum,2,'.',' '));
    }
    
    $(function() {
        $('#resurse_selling_dealing_count').change(updateResSelDealSum).keyup(updateResSelDealSum);
        updateResSelDealSum();
    })
    
</script>