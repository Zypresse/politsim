<?php
/* @var $costs app\models\resources\ResourceCost[] */
/* @var $resProto app\models\resources\proto\ResourceProto */
/* @var $readOnly boolean */

use app\components\MyHtmlHelper,
    app\models\Place;
?>
<div class="box">
    <div class="box-header">
        <span class="title"><?= $resProto->getHtmlName() ?></span>

        <ul class="box-toolbar">
            <li><span class="label label-<?= count($costs) > 0 ? 'green' : 'red' ?>"><?= MyHtmlHelper::formateNumberword(count($costs), "предложений", "предложение", "предложения") ?></span></li>
        </ul>
    </div>
    <div class="box-content">
        <div id="dataTables">

            <table class="table table-normal">
                <thead>
                    <tr>
                        <td>Продавец</td>
                        <td style="min-width: 250px">Регион</td>
                        <?php if ($resProto->isStorable()):?>
                        <td>Доступно</td>
                        <td style="min-width: 70px">Качество</td>
                        <?php endif ?>
                        <td style="min-width: 70px">Цена</td>
                        <?php if (!$readOnly && $resProto->isStorable()): ?>
                        <td>Действия</td>
                        <?php endif ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($costs as $cost): ?>
                        <tr>
                            <td>
                                <?= $cost->resource->place->object->getHtmlName() ?> 
                                <?= ($cost->resource->place->object->getPlaceType() === Place::TYPE_FACTORY) ? '(' . $cost->resource->place->object->holding->getHtmlName() . ')' : '' ?>
                            </td>
                            <td>
                                <?= ($cost->resource->place->object->getPlaceType() === Place::TYPE_FACTORY) ? $cost->resource->place->object->region->getHtmlName() : '' ?>
                            </td>
                            <?php if ($resProto->isStorable()):?>
                            <td>
                                <?= number_format($cost->resource->count, 0, '', ' ') ?> <?= $resProto->icon ?>
                            </td>
                            <td>
                                <?= MyHtmlHelper::oneTen2Stars($cost->resource->quality) ?>
                            </td>
                            <?php endif ?>
                            <td><?= MyHtmlHelper::moneyFormat($cost->cost, 2) ?></td>
                            <?php if (!$readOnly && $resProto->isStorable()): ?>
                            <td>
                                <button onclick="load_modal('resource-cost-info',{'id':<?=$cost->id?>,'unnp':$('#market-change-unnp-select').val()},'resource_cost_info','resource_cost_info_body')" class="btn btn-blue btn-xs">Покупка</button>
                            </td>
                            <?php endif ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php if (!$readOnly): ?>
<div style="display:none" class="modal fade" id="resource_cost_info" tabindex="-1" role="dialog" aria-labelledby="resource_cost_info_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="resource_cost_info_label">Информация о предложении</h3>
            </div>
            <div id="resource_cost_info_body" class="modal-body">
                <p>Загрузка…</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-blue" onclick="json_request('new-dealing-resource-selling',{'resource_cost_id':$('#resource_selling_dealing_cost_id').val(),'count':$('#resource_selling_dealing_count').val(),'unnp':$('#market-change-unnp-select').val()})" >Купить</button>
                <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<?php endif ?>