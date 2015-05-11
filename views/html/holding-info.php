<?php

/**
 * Holding info page
 * 
 * @var app\models\Holding $holding 
  **/

use app\components\MyHtmlHelper;
use yii\helpers\Html;
?>
<h1><?=htmlspecialchars($holding->name)?></h1>
<p>Капитализация: <?=MyHtmlHelper::aboutNumber($holding->capital)?> <?=MyHtmlHelper::icon('money')?></p>
<? if ($holding->state) { ?><p>Компания зарегистрирована в государстве: <?=Html::a($holding->state->name,'#',['onclick'=>"load_page('state-info',{'id':{$holding->state_id}})"])?></p><? } ?>
<h3>Акционеры компании:</h3>
<table class="table">
<? foreach ($holding->stocks as $stock) { 
    ?>
    <tr>
        <td><?
            switch (get_class($stock->master)) {
                case 'app\models\User':
                    echo Html::a($stock->master->name,'#',['onclick'=>"load_page('profile',{'uid':{$stock->master->id}})"]);
                break;
                case 'app\models\Post':
                    echo ($stock->master->ministry_name ? $stock->master->ministry_name : $stock->master->name).' ('.Html::a($stock->master->org->name,'#',['onclick'=>"load_page('org-info',{'id':{$stock->master->org_id}})"]).')';
                break;
                case 'app\models\Holding':
                    echo Html::a($stock->master->name,'#',['onclick'=>"load_page('holding-info',{'id':{$stock->master->id}})"]);
                break;
            }
        ?></td>
        <td><?=MyHtmlHelper::formateNumberword($stock->count, "акций","акция","акции")?> (<?=round($stock->getPercents(),2)?>%)</td>
    </tr>
<? } ?>
</table>
<h3>Недвижимость</h3>
<? if (count($holding->factories)) { ?>
<ul>
    <? foreach ($holding->factories as $factory) { ?>
    <li>
        <?=Html::a($factory->name,'#',['onclick'=>"load_page('factory-info',{'id':{$factory->id}})"])?> 
            <? if ($factory->status < 0) { ?><span style="color:red;">(не достроено, запланированная дата окончания строительства: <span class="formatDate" data-unixtime="<?=$factory->builded?>"><?=date('d-M-Y H:i',$factory->builded)?></span>)</span><? } ?>
            <? if ($factory->status > 1) { ?><span style="color:red;">(не работает)</span><? } ?>
    </li>
    <? } ?>
</ul>
<? } else { ?>
<p>Компания не владеет недвижимостью</p>
<? } ?>