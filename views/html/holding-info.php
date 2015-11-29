<?php

/**
 * Holding info page
 * 
 * @var app\models\Holding $holding 
  **/

use app\components\MyHtmlHelper;
use yii\helpers\Html;
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
<h1><?=htmlspecialchars($holding->name)?></h1>
<p>Капитализация: <?=MyHtmlHelper::aboutNumber($holding->capital)?> <?=MyHtmlHelper::icon('money')?></p>
<? if ($holding->state) { ?><p>Компания зарегистрирована в государстве: <?=Html::a($holding->state->name,'#',['onclick'=>"load_page('state-info',{'id':{$holding->state_id}})"])?></p><? } ?>
<h3>Акционеры компании:</h3>
<table class="table">
<? foreach ($holding->stocks as $stock) { 
    ?>
    <tr>
        <td><?=$stock->master->getHtmlName()?></td>
        <td><?=MyHtmlHelper::formateNumberword($stock->count, "акций","акция","акции")?> (<?=round($stock->getPercents(),2)?>%)</td>
    </tr>
<? } ?>
</table>
<h3>Недвижимость</h3>
<? if (count($holding->factories)) { ?>
<ul>
    <? foreach ($holding->factories as $factory) { ?>
    <li>
        <?=Html::a($factory->proto->name.' «'.$factory->name.'»','#',['onclick'=>"load_page('factory-info',{'id':{$factory->id}})"])?> 
            <? if ($factory->status < 0) { ?><span style="color:red;">(не достроено, запланированная дата окончания строительства: <span class="formatDate" data-unixtime="<?=$factory->builded?>"><?=date('d-M-Y H:i',$factory->builded)?></span>)</span><? } ?>
            <? if ($factory->status > 1) { ?><span style="color:red;">(не работает)</span><? } ?>
    </li>
    <? } ?>
</ul>
<? } else { ?>
<p>Компания не владеет недвижимостью</p>
<? } ?>
<h3>Инфраструктура</h3>
<? if (count($holding->lines)) { ?>
    <ul>
    <? foreach ($holding->lines as $line) { ?>
            <li>
                <?=$line->proto->name?> <?=$line->region1->name?> — <?=$line->region2->name?>
            </li>
            <? } ?>
    </ul>
    <? } else { ?>
    <p>Компания не владеет объектами инфраструктуры</p>
<? } ?>
        </div>
    </div>
</div>