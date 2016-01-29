<?php

/* @var $factory app\models\factories\Factory */
/* @var $resource app\models\resources\Resource */

use app\components\MyHtmlHelper;

?>
<h5>Установить цену продажи для ресурса <?= $resource->proto->icon ?> <?=$resource->proto->name?></h5>
<form id="form_resource_selling_cost">
    <input type="hidden" id="resource_for_selling_id" value="<?=$resource->id?>">
    <p>Цена за единицу: <input type="number" value="1" id="resource_for_selling_cost" > <?=MyHtmlHelper::icon('money')?></p>
    <p>
        <input checked="checked" id="resource_for_selling_type1" class="elect_vote_radio" type="radio" name="resource_for_selling_type" value="1">
        <label for="resource_for_selling_type1">Продавать кому угодно</label>
    </p>
    <p>
        <input id="resource_for_selling_type2" class="elect_vote_radio" type="radio" name="resource_for_selling_type" value="2">
        <label for="resource_for_selling_type2">Продавать только налогоплательщикам страны <?=$factory->region->state->getHtmlName()?></label>
    </p>
    <p>
        <input id="resource_for_selling_type3" class="elect_vote_radio" type="radio" name="resource_for_selling_type" value="3">
        <label for="resource_for_selling_type3">Продавать только фабрикам, принадлежащим <?=$factory->holding->getHtmlName()?></label>
    </p>
</form>
<!--<p>Всего на складе сейчас <?= number_format($resource->count, 0, '', ' ') ?> <?= $resource->proto->icon ?></p>-->
<!--<p>Потенциальный доход: <span id="potential_dohod"></span> <?=MyHtmlHelper::icon('money')?></p>-->

<script type="text/javascript">
    /*function calcDohod() {
        var dohod = parseFloat($("#resource_for_selling_cost").val()) * <?=$resource->count?>;
        $('#potential_dohod').text(number_format(dohod,2,'.',' '));
    }
    
    $('#resource_for_selling_cost').change(calcDohod).keyup(calcDohod);
    $(calcDohod);*/
    
    $(function(){
        $('.elect_vote_radio').iCheck({
            checkboxClass: 'icheckbox_square',
            radioClass: 'iradio_square',
            increaseArea: '20%' // optional
        });
    });
</script>