
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h4>Демографическая карта</h4>
            <div id="mapdiv" style="width: 100%; height: 500px;background-color:#EEEEEE; "></div>
        </div>
    </div>
</div>
<script>
    var region, map;

    function set_regions_names() {
<?php foreach ($regions as $i => $region) { ?>map.regions['<?= $region->code ?>'].config.name = '<?= htmlspecialchars($region->name) ?><?php if ($region->state) { ?> (<?= htmlspecialchars($region->state->short_name) ?>)<?php } ?>';<?php } ?>
      }
      $(function () {



          if (map)
              map.remove();

          regions = {};
<?php foreach ($regions as $i => $region) { ?>
              regions['<?= $region->code ?>'] = <?= $region->population ?>;
<?php } ?>

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
                          normalizeFunction: 'polynomial',
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
                  load_modal('region-population', {'code': region}, 'region_info', 'region_info_body');
              }
          });

          map = $('#mapdiv').vectorMap('get', 'mapObject');
          set_regions_names();




      });
</script>
