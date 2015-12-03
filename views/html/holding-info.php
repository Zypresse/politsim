<?php

/* @var $holding app\models\Holding */
/* @var $factories app\models\factories\Factory[] */

use app\components\MyHtmlHelper;

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1><?=htmlspecialchars($holding->name)?></h1>
            <p>Директор: <?= $holding->director ? $holding->director->getHtmlName() : '<em>не назначен</em>' ?></p>
            <p>Примерная капитализация: <span class="status-success"><?=MyHtmlHelper::aboutNumber($holding->capital)?> <?=MyHtmlHelper::icon('money')?></span></p>
            <? if ($holding->state) { ?><p>Компания зарегистрирована в государстве <?=$holding->state->getHtmlName()?></p><? } ?>
            <? if ($holding->region) { ?><p>Компания имеет головной офис в городе <?=$holding->region->getCityHtmlName()?></p><? } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                    <span class="title">
                        <i class="icon-building"></i> Недвижимость
                    </span>
                </div>
                <div class="box-content">    
                    <table class="table table-normal">
                    <? if (count($factories)): ?>
                        <thead>
                            <tr>
                                <td>Предприятие</td>
                                <td style="min-width:150px">Регион</td>
                            </tr>
                        </thead>
                        <tbody>
                            <? foreach ($factories as $factory): ?>
                            <tr>
                                <td><?=$factory->getHtmlName()?></td>
                                <td><?=$factory->region->getHtmlName()?></td>
                            </tr>
                            <? endforeach ?>
                    <? else: ?>
                        <tbody>
                            <tr>
                                <td>Компания не владеет недвижимостью</td>
                            </tr>
                    <? endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>