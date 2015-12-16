<?php

/* @var $holding app\models\Holding */
/* @var $regions app\models\Region[] */

?>

<div class="control-group" >
    <label class="control-label" for="#build-factory-modal-region-id">Место строительства</label>
    <div class="controls">
        <select id="build-factory-modal-region-id">
        <? foreach ($regions as $i => $region): ?>
            <? if ($i == 0 || $regions[$i - 1]->state_id != $region->state_id): ?>
                <?= ($i) ? '</optgroup>' : '' ?><optgroup label="<?= ($region->state) ? $region->state->name : 'Ничейные регионы' ?>">
            <? endif ?>
            <option value="<?= $region->id ?>" <?= ((!$holding->region_id && $region->state_id === $holding->state_id && $region->isCapital()) || $region->id === $holding->region_id) ? "selected='selected'" : '' ?>><?= $region->name ?></option>
        <? endforeach ?>
        </select>
    </div>
</div>
<script type="text/javascript">

    $(function(){
        
        $('#build-factory-modal-main-btn').off('click');
        $('#build-factory-modal-main-btn').click(function(){
            load_modal('build-factory',{
                'region_id': $('#build-factory-modal-region-id').val(),
                'holding_id': <?=$holding->id?>
            },'build-factory-modal','build-factory-modal-body',function(){
                $('#build-factory-modal-main-btn').text('Построить');
            })
        })
    })
    
</script>