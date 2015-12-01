<?php

/* @var $factory app\models\factories\Factory */
/* @var $resurse app\models\resurses\Resurse */
/* @var $settings app\models\factories\FactoryAutobuySettings */

use app\components\MyHtmlHelper;

?>
<h5>Установить правила автозакупки ресурса <?= MyHtmlHelper::icon($resurse->proto->class_name) ?> <?=$resurse->proto->name?></h5>

    <input type="hidden" id="resurse_for_autobuy_id" value="<?=$resurse->id?>">
    <p>
        <input <?=$settings->id?'checked="checked"':''?> id="resurse_autobuy_on" class="elect_vote_radio" type="checkbox" value="1">
        <label for="resurse_autobuy_on">Включить автозакупку ресурса каждый час</label>
    </p>
    <div id="hide_if_autobuy_off" <?=$settings->id?'':'style="display: none"'?>>
        <form id="form_resurse_autobuy_settings">
            <p>Максимальная цена для закупки: <input type="number" value="<?=$settings->max_cost?$settings->max_cost:1?>" id="resurse_for_autobuy_cost" > <?=MyHtmlHelper::icon('money')?></p>
            <p>
                <input <?=!$settings->holding_id&&!$settings->state_id?'checked="checked"':''?> id="resurse_for_autobuy_type1" class="elect_vote_radio" type="radio" name="resurse_for_autobuy_type" value="1">
                <label for="resurse_for_autobuy_type1">Закупать у кого угодно</label>
            </p>
            <p>
                <input <?=$settings->state_id?'checked="checked"':''?> id="resurse_for_autobuy_type2" class="elect_vote_radio" type="radio" name="resurse_for_autobuy_type" value="2">
                <label for="resurse_for_autobuy_type2">Закупать только у налогоплательщиков страны <?=$factory->region->state->getHtmlName()?></label>
            </p>
            <p>
                <input <?=$settings->holding_id?'checked="checked"':''?> id="resurse_for_autobuy_type3" class="elect_vote_radio" type="radio" name="resurse_for_autobuy_type" value="3">
                <label for="resurse_for_autobuy_type3">Закупать только у фабрик, принадлежащих <?=$factory->holding->getHtmlName()?></label>
            </p>
        </form>
    </div>
<script type="text/javascript">    
    $(function(){
        $('.elect_vote_radio').iCheck({
            checkboxClass: 'icheckbox_square',
            radioClass: 'iradio_square',
            increaseArea: '20%' // optional
        });
        
        $('#resurse_autobuy_on').on('ifChanged', function(event){
            if ($(this).is(":checked")) {
                $('#hide_if_autobuy_off').slideDown();
            } else {
                $('#hide_if_autobuy_off').slideUp();
            }
        });
    });
</script>