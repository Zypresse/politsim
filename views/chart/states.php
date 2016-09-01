<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $list app\models\State[] */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'States chart')?>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-th-list"></i> <?=Yii::t('app', 'Charts')?></li>
        <li class="active"><?=Yii::t('app', 'States chart')?></li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <table id="chart_states" class="table table-bordered table-hover">
                <thead>
                    <tr><th>Флаг</th><th style="min-width: 33%; max-width: 50%;">Название</th><th>Население</th></tr>
                </thead>
                <tbody>
                <?php foreach ($list as $state): ?>
                    <tr>
                        <td><?=Html::img($state->flag)?></td>
                        <td><a href="#" onclick="load_page('state-info', {'id':<?= $state->id ?>})"><?= Html::encode($state->name) ?></a></td>
                        <td><i class="fa fa-group"></i> <?= MyHtmlHelper::formateNumberword($state->population, 'h') ?></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script type="text/javascript">

$(function(){
    $("#chart_states").DataTable({
        ordering: false,
        language: {
            paginate: {
                first:    '«',
                previous: '‹',
                next:     '›',
                last:     '»'
            },
            aria: {
                paginate: {
                    first:    '<?=Yii::t('app','First')?>',
                    previous: '<?=Yii::t('app','Previous')?>',
                    next:     '<?=Yii::t('app','Next')?>',
                    last:     '<?=Yii::t('app','Last')?>'
                },
                sortAscending: '<?=Yii::t('app',' - click/return to sort ascending')?>',
                sortDescending: '<?=Yii::t('app',' - click/return to sort descending')?>'
            },
            decimal: ',',
            thousands: '.',
            emptyTable: '<?=Yii::t('app','No data available in table')?>',
            info: '<?=Yii::t('app','Showing _START_ to _END_ of _TOTAL_ states')?>',
            infoEmpty: '<?=Yii::t('app','No entries to show')?>',
            infoFiltered: '<?=Yii::t('app','(filtered from _MAX_ total states)')?>',
            lengthMenu: '<?=Yii::t('app','Show <select><option value="10">10</option><option value="20">20</option><option value="30">30</option><option value="40">40</option><option value="50">50</option><option value="-1">All</option></select> states')?>',
            processing: '<?=Yii::t('app','Processing...')?>',
            search: '<?=Yii::t('app','Search:')?>',
            searchPlaceholder: '<?=Yii::t('app','State name')?>',
            zeroRecords: '<?=Yii::t('app','No matching states found')?>'
        }
    });
});

</script>