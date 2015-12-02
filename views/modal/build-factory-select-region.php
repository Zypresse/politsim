<?php

/* @var $viewer app\models\Holding */
/* @var $regions app\models\Region[] */

?>

<div class="control-group" >
    <label class="control-label" for="#factory_new_region">Место строительства</label>
    <div class="controls">
        <select id="factory_new_region">
        <? foreach ($regions as $i => $region): ?>
            <? if ($i == 0 || $regions[$i - 1]->state_id != $region->state_id): ?>
                <?= ($i) ? '</optgroup>' : '' ?><optgroup label="<?= ($region->state) ? $region->state->name : 'Ничейные регионы' ?>">
            <? endif ?>
            <option value="<?= $region->id ?>" <?= ((!$viewer->region_id && $region->state_id === $viewer->state_id && $region->isCapital()) || $region->id === $viewer->region_id) ? "selected='selected'" : '' ?>><?= $region->name ?></option>
        <? endforeach ?>
        </select>
    </div>
</div>

<div id="factory_new_region_map" style="width: 100%; height: 300px;background-color:#EEEEEE; "></div>

<script type="text/javascript">
    
    var map;
    
    $(function(){
        $('#factory_new_region_map').vectorMap({
            map: 'map'+localStorage.getItem("MAP_VERSION"),
            backgroundColor: '#4B6099',
            zoomMax:50,
            focusOn: {
                x: 0.5,
                y: 0.5,
                scale: 1
            },
            markerStyle: {
                initial: {
                  fill: '#dddddd',
                  stroke: 'black',
                  "stroke-width": 1,
                },
                hover: {
                  stroke: 'black',
                  "stroke-width": 2,
                },
                selected: {
                  stroke: 'black',
                  "stroke-width": 2,

                }
            },
            series: {
                regions: [{
                    attribute: 'fill',
                    values: {
                        <? foreach ($regions as $i => $region) {  
                            if ($i) echo ",";  
                            $color = '#eee'; 
                            echo "'{$region->code}': '{$color}'"; 
                        } ?>
                    }
                }]
            },
            onRegionClick: function(event, code){
              console.log(code);
            }
        });

        map = $('#factory_new_region_map').vectorMap('get', 'mapObject');

        set_regions_names();
    });
    
    function set_regions_names() {
    <?php foreach ($regions as $i => $region) { ?>
        map.regions['<?=$region->code?>'].config.name = '<?=htmlspecialchars($region->name)?>';
    <? } ?>
    }
</script>