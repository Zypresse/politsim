<?php

use app\helpers\Html;

/* @var $this yii\base\View */
/* @var $list app\models\government\State[] */

?>
<section class="content-header">
    <h1>
        <?= Yii::t('app', 'States chart') ?>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-th-list"></i> <?= Yii::t('app', 'Charts') ?></li>
        <li class="active"><?= Yii::t('app', 'States chart') ?></li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <table id="chart_states" class="table table-bordered table-hover">
                <thead>
                    <tr><th style="width:70px">Флаг</th><th style="min-width: 33%; max-width: 50%;">Название</th><th>Население</th><th>Территория</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $state): ?>
                        <tr>
                            <td class="text-center"><?= Html::img($state->flag, ['style' => 'max-width: 100px; height: 20px']) ?></td>
                            <td><a href="#!state&id=<?= $state->id ?>"><?= Html::encode($state->name) ?></a></td>
                            <td><i class="fa fa-group"></i> <?= Html::numberWord($state->population, 'h') ?></td>
                            <td><i class="fa fa-map"></i> <?= $state->area ?> кв.км.</td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script type="text/javascript">

    $(function () {
        $("#chart_states").DataTable({
            ordering: false,
            language: datatable_language
        });
    });

</script>