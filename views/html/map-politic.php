
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h4>Политическая карта</h4>
            <div id="mapdiv" style="width: 100%; height: 500px;background-color:#EEEEEE; "></div>
        </div>
    </div>
</section>
<script>
    var region, map;
    $(function () {
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
                        attribute: 'fill',
                        values: {
<?php foreach ($regions as $i => $region) {
    if ($i)
        echo ",";
    $color = $region->state ? $region->state->color : '#eee';
    echo "'{$region->code}': '{$color}'";
}
?>
                        }
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
                show_region(region);
            }
        });


        map = $('#mapdiv').vectorMap('get', 'mapObject');

        set_regions_names();


    });
    function set_regions_names() {
<?php foreach ($regions as $i => $region) { ?>map.regions['<?= $region->code ?>'].config.name = '<?= htmlspecialchars($region->name) ?><?php if ($region->state) { ?> (<?= htmlspecialchars($region->state->short_name) ?>)<?php } ?>';<?php } ?>
    }

</script>
