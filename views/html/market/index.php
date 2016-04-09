<?php

/* @var $this yii\web\View */
/* @var $user app\models\User */

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?= $this->render('_menu', ['active' => 0]) ?>
            <h3>Мировой финансовый рынок</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <span class="title"><i class="icon-bar-chart"></i> Мировая добыча полезных ископаемых</span>
                </div>
                <div class="box-content">
                    <div id="world_mining" style="height:200px"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <span class="title"><i class="icon-bar-chart"></i> Динамика цен на полезные ископаемые</span>
                </div>
                <div class="box-content">
                    <div id="world_prices" style="height:200px"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        var worldMiningChart = new google.visualization.ColumnChart($('#world_mining')[0]);

        worldMiningChart.draw(google.visualization.arrayToDataTable([
            ['Время', 'Нефть', 'Газ'],
            <?php foreach ($statisticsWorldMining['Oil'] as $i => $oilStat): $gasStat = $statisticsWorldMining['NaturalGas'][$i];?>
                <?=$i?',':''?>["<?=date("H:i",$oilStat->timestamp)?>", <?=$oilStat->value?>, <?=$gasStat->value?>]
            <?php endforeach ?>
        ]), {            
            colors: [Theme.colors.red, Theme.colors.blue],
            legend: {position: 'top'}
        });
        
        var worldPricesChart = new google.visualization.LineChart($('#world_prices')[0]);

        worldPricesChart.draw(google.visualization.arrayToDataTable([
            ['Время', 'Нефть', 'Газ'],
            <?php foreach ($statisticsWorldCosts['Oil'] as $i => $oilStat): $gasStat = $statisticsWorldCosts['NaturalGas'][$i];?>
                <?=$i?',':''?>["<?=date("H:i",$oilStat->timestamp)?>", <?=$oilStat->value?>, <?=$gasStat->value?>]
            <?php endforeach ?>
        ]), {            
            colors: [Theme.colors.red, Theme.colors.blue],
            legend: {position: 'top'}
        });
    });

</script>