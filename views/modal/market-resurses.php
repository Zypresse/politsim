<?php
/* @var $costs app\models\resurses\ResurseCost[] */
/* @var $resProto app\models\resurses\proto\ResurseProto */
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
                        <td>Доступно</td>
                        <td style="min-width: 70px">Качество</td>
                        <td style="min-width: 70px">Цена</td>
                        <? if (!$readOnly): ?><td>Действия</td><? endif ?>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($costs as $cost): ?>
                        <tr>
                            <td>
                                <?= $cost->resurse->place->getHtmlName() ?> 
                                <?= ($cost->resurse->place->getPlaceType() === Place::TYPE_FACTORY) ? '(' . $cost->resurse->place->holding->getHtmlName() . ')' : ''
                                ?>
                            </td>
                            <td>
                                <?= ($cost->resurse->place->getPlaceType() === Place::TYPE_FACTORY) ? $cost->resurse->place->region->getHtmlName() : ''
                                ?>
                            </td>
                            <td>
                                <?= number_format($cost->resurse->count, 0, '', ' ') ?> <?= MyHtmlHelper::icon($resProto->class_name) ?>
                            </td>
                            <td><?= MyHtmlHelper::oneTen2Stars($cost->resurse->quality) ?></td>
                            <td><?= MyHtmlHelper::moneyFormat($cost->cost, 2) ?></td>
                            <? if (!$readOnly): ?>
                            <td>
                                <button onclick="load_modal('resurse-cost-info',{'id':<?=$cost->id?>,'unnp':$('#market-change-unnp-select').val()},'resurse_cost_info','resurse_cost_info_body')" class="btn btn-blue btn-xs">Покупка</button>
                            </td>
                            <? endif ?>
                        </tr>
                    <? endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<? if (!$readOnly): ?>
<div style="display:none" class="modal fade" id="resurse_cost_info" tabindex="-1" role="dialog" aria-labelledby="resurse_cost_info_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="resurse_cost_info_label">Информация о предложении</h3>
            </div>
            <div id="resurse_cost_info_body" class="modal-body">
                <p>Загрузка…</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-blue" onclick="json_request('new-dealing-resurse-selling',{'resurse_cost_id':$('#resurse_selling_dealing_cost_id').val(),'count':$('#resurse_selling_dealing_count').val(),'unnp':$('#market-change-unnp-select').val()})" >Купить</button>
                <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<? endif ?>