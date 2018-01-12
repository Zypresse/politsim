<?php 
    use yii\helpers\Html,
        app\models\CoreCountry;

    $cores = CoreCountry::find()->all();
    
?>

<section class="content">
    <div class="row">
        <div class="col-md-12">
<h4>Карта претензий</h4>
<div class="btn-group">
    <?php foreach ($cores as $core) { ?>
  <button class="btn btn-sm dropdown-toggle btn-default" onclick="show_cores<?=$core->id?>()" >
      <?=Html::img('/img/cores/'.$core->id.'.png',['alt'=>$core->name,'title'=>$core->name]);?>
  </button>
    <?php } ?>
</div>
<br>
<div id="mapdiv" style="width: 100%; height: 500px;background-color:#EEEEEE; "></div> </div>

    </div>
</section>
<script>
var region,map;
$(function(){
$('#mapdiv').vectorMap({
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
            <?php foreach ($regions as $i => $region) {  if ($i) echo ",";  $color = '#eee'; echo "'{$region->code}': '{$color}'"; } ?>
            }
	      }]
	    },
        onRegionOver: function(event, code){
        //  console.log('region-over', code, $('#mapdiv').vectorMap('get', 'mapObject').getRegionName(code));
        },
        onRegionOut: function(event, code){
         // console.log('region-out', code);
        }/*, onRegionClick: function(event, code){
          console.log(code);
        },
        onMarkerClick: function(event, index){
console.log(index);
        	
        }*/
        
      });


    map = $('#mapdiv').vectorMap('get', 'mapObject');
    
     /*   map.container.click(function(e){
      var latLng = map.pointToLatLng(e.offsetX, e.offsetY)
         console.log(latLng);});*/
set_regions_names();


});
 function set_regions_names() {
  <?php foreach ($regions as $i => $region) { ?>map.regions['<?=$region->code?>'].config.name = '<?=htmlspecialchars($region->name)?>';<?php } ?>
 }
 <?php     
    foreach ($cores as $core) { ?>
        function show_cores<?=$core->id?>() {
            map.removeAllMarkers();
            <?php foreach ($core->regions as $region) { ?>
                map.addMarker("core_region<?=$region->id?>",{ latLng: [<?=$region->lat?>,<?=$region->lng?>], name: "<?=$region->name?>", image: '/img/cores/<?=$core->id?>.png', style: {width:16,height:16} });
            <?php } ?>
                map.series.regions[0].setValues({
                    <?php foreach ($regions as $i => $region) { ?>
                        <?=($i?',':'')?> '<?=$region->code?>':'#eee'
                    <?php } ?>
                    <?php foreach ($core->states as $state) { ?>
                    <?php foreach ($state->regions as $i => $region) { ?>
                        , '<?=$region->code?>':'<?=$state->color?>'
                    <?php }} ?>
                });
        }
    <?php } ?>

</script>
