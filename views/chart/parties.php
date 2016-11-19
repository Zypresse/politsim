<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $list app\models\Party[] */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Parties chart')?>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-th-list"></i> <?=Yii::t('app', 'Charts')?></li>
        <li class="active"><?=Yii::t('app', 'Parties chart')?></li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <table id="chart_parties" class="table table-bordered table-hover">
                <thead>
                    <tr><th style="width:70px">Флаг</th><th style="min-width: 33%; max-width: 50%;">Название</th><th>Число участников</th><th>Страна</th></tr>
                </thead>
                <tbody>
                <?php foreach ($list as $party): ?>
                    <tr>
                        <td class="text-center"><?=$party->flag ? Html::img($party->flag, ['style' => 'max-width: 100px; height: 20px']) : ''?></td>
                        <td><a href="#!party&id=<?=$party->id?>"><?= Html::encode($party->name) ?></a></td>
                        <td><i class="fa fa-group"></i> <?= MyHtmlHelper::formateNumberword($party->membersCount, 'h') ?></td>
                        <td><?=LinkCreator::stateLink($party->state)?></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script type="text/javascript">

$(function(){
    $("#chart_parties").DataTable({
        ordering: false,
        language: datatable_language
    });
});

</script>