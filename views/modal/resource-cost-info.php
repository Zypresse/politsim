<?php

/* @var $resCost app\models\resources\ResourceCost */
/* @var $viewer app\models\factories\Factory */

use app\components\MyHtmlHelper,
    app\models\Region;

$transferCost = round($resCost->resource->place->object->region->calcDist($viewer->region)*Region::TRANSFER_COST);

?>
<input type="hidden" id="resource_selling_dealing_cost_id" value="<?=$resCost->id?>">
<p><?=$viewer->getHtmlName()?> (<?=$viewer->holding->getHtmlName()?>) собирается приобрести у <?=$resCost->resource->place->object->getHtmlName()?> (<?=$resCost->resource->place->object->holding->getHtmlName()?>)</p>
<p><input id="resource_selling_dealing_count" type="number" value="<?=$resCost->resource->count?>" > <?=$resCost->resource->proto->icon?></p>
<p>по цене в <?=MyHtmlHelper::moneyFormat($resCost->cost)?> за единицу.</p>
<p>Доставка из <?=$resCost->resource->place->object->region->getHtmlName()?> в <?=$viewer->holding->region->getHtmlName()?> обойдётся в <?=number_format($transferCost,0,'',' ')?> <?=MyHtmlHelper::icon('money')?></p>
<p>Итого: <span id="resource_selling_dealing_sum"></span> <?=MyHtmlHelper::icon('money')?></p>
<script type="text/javascript">

    function updateResSelDealSum() {
        var count = parseFloat($('#resource_selling_dealing_count').val());
        if (count <= 0 || isNaN(count)) {
            count = 1;
        }
        if (count > <?=$resCost->resource->count?>) {
            count = <?=$resCost->resource->count?>;
        }
        $('#resource_selling_dealing_count').val(count);
        
        var sum = <?=$resCost->cost?> * count + <?=$transferCost?>;
        $('#resource_selling_dealing_sum').text(number_format(sum,2,'.',' '));
    }
    
    $(function() {
        $('#resource_selling_dealing_count').change(updateResSelDealSum).keyup(updateResSelDealSum);
        updateResSelDealSum();
    })
    
</script>