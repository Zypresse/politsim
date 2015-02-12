<div class="span12">
<h4>Демографическая карта</h4>
<div id="mapdiv" style="width: 100%; height: 500px;background-color:#EEEEEE; "></div> </div>
<div style="display:none" class="modal" id="region_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Информация о регионе</h3>
  </div>
  <div id="region_info_body" class="modal-body">
    <p>Загрузка…</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    <!--<button class="btn btn-primary">Save changes</button>-->
  </div>
</div>
<script>
var region,map;

function set_regions_names() {
  <? foreach ($regions as $i => $region) { ?>map.regions['<?=$region->code?>'].config.name = '<?=htmlspecialchars($region->name)?><? if ($region->state) { ?> (<?=htmlspecialchars($region->state->short_name)?>)<?}?>';<? } ?>
}
$(function(){



    if (map) map.remove();

    regions = {};
    <? foreach ($regions as $i => $region) { ?>
      regions['<?=$region->code?>'] = <?=$region->population?>;
    <? } ?>

    $('#mapdiv').vectorMap({
          map: 'map4',
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
          scale: ['#ffffff', '#ee0000'],
          normalizeFunction: 'polynomial',
          values: regions
        }]
      },
        onRegionOver: function(event, code){
        //  console.log('region-over', code, $('#mapdiv').vectorMap('get', 'mapObject').getRegionName(code));
        },
        onRegionOut: function(event, code){
         // console.log('region-out', code);
        }
        ,onRegionClick: function(event, region){
         // console.log('region-click', region);
          $.ajax(
            {
              url: '/nodejs?a=region_population_info&region='+region,
              beforeSend:function() {
                  $('#region_info_body').empty();
              },
              success:function(d) {
                  $('#region_info_body').html(d);
                  $('#region_info').modal();
              },
                error:show_error
        });          
        }
      });

    map = $('#mapdiv').vectorMap('get', 'mapObject');
    set_regions_names();

 


});
</script>
