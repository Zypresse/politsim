<div class="span12">
<h4>Запасы ресурсов по регионам</h4>
<p>
<? foreach ($resurses as $i => $res) { ?>
  <button type="button" class="btn btn-default <? if ($i === 0) { ?>active<? } ?> economic_map_btn" data-code="<?=$res->code?>" ><img src="/img/<?=$res->code?>.png" alt="<?=$res->name?>" title="<?=$res->name?>"></button>
<? } ?>
</p>
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
$(function(){

load_resurses_map('oil');

$('.economic_map_btn').click(function(){
  $('.economic_map_btn').removeClass('active');
  $(this).addClass('active');
  load_resurses_map($(this).data('code'));
})

});
function set_regions_names() {
  <? foreach ($regions as $i => $region) { ?>map.regions['<?=$region->code?>'].config.name = '<?=htmlspecialchars($region->name)?><? if ($region->state) { ?> (<?=htmlspecialchars($region->state->short_name)?>)<?}?>';<? } ?>
}

 function load_resurses_map(resurse) {
  get_json('regions-resurses',{'code':resurse},function(rows){


    if (map) map.remove();

    regions = {};
    for (var i=0,l=rows.length;i<l;i++) {
      regions[rows[i].code] = rows[i][resurse];
    }

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
          normalizeFunction: 'linear',
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
              url: '/api/modal/region-resurses?code='+region,
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
  })
 }
</script>
