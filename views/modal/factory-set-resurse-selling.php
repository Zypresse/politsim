<?php

/* @var $factory app\models\factories\Factory */
/* @var $resurse app\models\resurses\Resurse */

use app\components\MyHtmlHelper;

?>
<h5>Выставить на продажу <?= MyHtmlHelper::icon($resurse->proto->class_name) ?> <?=$resurse->proto->name?></h5>
<form id="form_resurse_selling_cost">
    <input type="hidden" id="resurse_for_selling_id" value="<?=$resurse->id?>">
    <p>Цена за единицу: <input type="number" value="1" id="resurse_for_selling_cost" > <?=MyHtmlHelper::icon('money')?></p>
    <p>
        <input checked="checked" id="resurse_for_selling_type1" class="elect_vote_radio" type="radio" name="resurse_for_selling_type" value="1">
        <label for="resurse_for_selling_type1">Продавать кому угодно</label>
    </p>
    <p>
        <input id="resurse_for_selling_type2" class="elect_vote_radio" type="radio" name="resurse_for_selling_type" value="2">
        <label for="resurse_for_selling_type2">Продавать только налогоплательщикам страны <?=$resurse->place->region->state->getHtmlName()?></label>
    </p>
    <p>
        <input id="resurse_for_selling_type3" class="elect_vote_radio" type="radio" name="resurse_for_selling_type" value="3">
        <label for="resurse_for_selling_type3">Продавать только фабрикам, принадлежащим <?=$resurse->place->holding->getHtmlName()?></label>
    </p>
</form>
<p>Всего на складе сейчас <?= number_format($resurse->count, 0, '', ' ') ?> <?= MyHtmlHelper::icon($resurse->proto->class_name) ?></p>
<p>Потенциальный доход: <span id="potential_dohod"></span> <?=MyHtmlHelper::icon('money')?></p>

<script type="text/javascript">
    function calcDohod() {
        var dohod = parseFloat($("#resurse_for_selling_cost").val()) * <?=$resurse->count?>;
        $('#potential_dohod').text(number_format(dohod,2,'.',' '));
    }
    
    $('#resurse_for_selling_cost').change(calcDohod).keyup(calcDohod);
    $(calcDohod);
    
    $(function(){
        $('.elect_vote_radio').iCheck({
            checkboxClass: 'icheckbox_square',
            radioClass: 'iradio_square',
            increaseArea: '20%' // optional
        });
    });
</script>