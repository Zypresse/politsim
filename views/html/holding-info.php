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
<p>Капитализация: <?=number_format($holding->capital,0,'',' ')?> <?=MyHtmlHelper::icon('coins')?></p>
<? if ($user->isShareholder($holding)) { ?><p>Баланс: <?=number_format($holding->balance,0,'',' ')?> <?=MyHtmlHelper::icon('coins')?></p><? } ?>
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
                    echo $stock->master->name.' ('.Html::a($stock->master->org->name,'#',['onclick'=>"load_page('org-info',{'id':{$stock->master->org_id}})"]).')';
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