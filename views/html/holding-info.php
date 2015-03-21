<?php

/**
 * Holding info page
 * 
 * @var app\models\Holding $holding 
 * @var app\models\User $user 
 **/

use app\components\MyHtmlHelper;
use yii\helpers\Html;
?>
<h1><?=htmlspecialchars($holding->name)?></h1>
<p>Капитализация: <?=MyHtmlHelper::aboutNumber($holding->capital)?> <?=MyHtmlHelper::icon('money')?></p>
<? if ($user->isShareholder($holding)) { ?><p>Баланс: <?=number_format($holding->balance,0,'',' ')?> <?=MyHtmlHelper::icon('money')?></p><? } ?>
<p>Компания зарегистрирована в государстве: <?=Html::a($holding->state->name,'#',['onclick'=>"load_page('state-info',{'id':{$holding->state_id}})"])?></p>
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