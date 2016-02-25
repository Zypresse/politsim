<?php

/* @var $resources app\models\resources\proto\ResourceProto[] */

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h4>Эффективность добычи ресурсов по регионам</h4>
            <p>
                Выберите ресурс:
                <?php foreach ($resources as $i => $res) { ?>
                    <button class="btn btn-xs btn-default <?php if ($i === 0) { ?>btn-lightblue<?php } ?> economic_map_btn" data-id="<?= $res->id ?>" ><?=$res->icon?></button>
                <?php } ?>
            </p>
            <div id="mapdiv" style="width: 100%; height: 500px;background-color:#EEEEEE; "></div>
        </div>
    </div>
</div>
<script>
    var region, map;
    $(function () {

        load_resources_map(1);

        $('.economic_map_btn').click(function () {
            $('.economic_map_btn').removeClass('btn-lightblue');
            $(this).addClass('btn-lightblue');
            load_resources_map($(this).data('id'));
        })

    });
    function set_regions_names() {
<?php foreach ($regions as $i => $region) { ?>map.regions['<?= $region->code ?>'].config.name = '<?= htmlspecialchars($region->name) ?><?php if ($region->state) { ?> (<?= htmlspecialchars($region->state->short_name) ?>)<?php } ?>';<?php } ?>
    }

    function load_resources_map(resource) {
        get_json('regions-resources', {'id': resource}, function (rows) {


            if (map)
                map.remove();

            var regions = {};
            for (var i = 0, l = rows.result.length; i < l; i++) {
                regions[rows.result[i].region] = rows.result[i].count;
            }

            $('#mapdiv').vectorMap({
                map: 'map' + localStorage.getItem("MAP_VERSION"),
                backgroundColor: '#4B6099',
                zoomMax: 50,
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
                            scale: ['#ffffff', '#ee0000'],
                            normalizeFunction: 'linear',
                            values: regions
                        }]
                },
                onRegionOver: function (event, code) {
                    //  console.log('region-over', code, $('#mapdiv').vectorMap('get', 'mapObject').getRegionName(code));
                },
                onRegionOut: function (event, code) {
                    // console.log('region-out', code);
                }
                , onRegionClick: function (event, region) {
                    // console.log('region-click', region);
                    load_modal('region-resources', {'code': region}, 'region_info', 'region_info_body');
                }
            });

            map = $('#mapdiv').vectorMap('get', 'mapObject');
            set_regions_names();
        })
    }
</script>
