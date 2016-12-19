<?php

/* @var $width integer */
/* @var $height integer */
/* @var $colors array */
/* @var $data array */
/* @var $numbers string */
/* @var $table array */
/* @var $colName string */

?>
<div class="row">
    <div class="col-md-4" atyle="padding-top:100px">
        <span class="pie" data-peity='{"height":<?=$height?>, "width":<?=$width?>, "fill":<?=json_encode($colors)?>}' ><?=$numbers?></span>
    </div>
    <div class="col-md-8">
        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th><?= $colName ?></th>
                    <th><?= Yii::t('app', 'Percents') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; foreach ($table as $object): ?>
                    <tr>
                        <td style="width: 30px; background-color: <?= $object['color'] ?>"></td>
                        <td><?= $object['name'] ?></td>
                        <td><?= $object['percents'] ?>%</td>
                    </tr>
                <?php $i++; endforeach ?>
            </tbody>
        </table>
    </div>
</div>
