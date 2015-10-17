<?php
    use app\components\MyHtmlHelper;
    
    /* @var $this yii\web\View */
?>
<h1>Мировой финансовый рынок</h1>
<?=$this->render('_menu',['active' => 0])?>
<div id="world_gdp" class="span6"></div>
<div id="world_mining" class="span6"></div>

<script type="text/javascript">
    $(function(){
        var worldGdpChart = new google.visualization.LineChart($('#world_gdp')[0]);
        var worldMiningChart = new google.visualization.LineChart($('#world_mining')[0]);

        worldGdpChart.draw(google.visualization.arrayToDataTable([
          ['День', 'Мировой ВВП'],
          ['14.10.2015',  0],
          ['15.10.2015',  0],
          ['16.10.2015',  0],
          ['17.10.2015',  0],
        ]), {
          title: 'Мировая экономика',
          curveType: 'function',
          legend: { position: 'bottom' }
        });
        
        worldMiningChart.draw(google.visualization.arrayToDataTable([
          ['День', 'Нефть', 'Газ'],
          ['14.10.2015',  0, 0],
          ['15.10.2015',  0, 0],
          ['16.10.2015',  0, 0],
          ['17.10.2015',  0, 0],
        ]), {
          title: 'Добыча нефти и газа',
          curveType: 'function',
          legend: { position: 'bottom' }
        });
        
    });   
    
</script>