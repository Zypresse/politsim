<?php

use app\helpers\Html;

/* @var $this \yii\web\View */
/* @var $list app\models\politics\Organization[] */

$this->title = 'Рейтинг организаций';

?>
<section class="content-header">
    <h1>
        Рейтинг организаций
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-th-list"></i> <?= Yii::t('app', 'Charts') ?></li>
        <li class="active">Рейтинг организаций</li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <table id="chart_states" class="table table-bordered table-hover">
                <thead>
                    <tr><th style="width:70px">Флаг</th><th style="min-width: 33%; max-width: 50%;">Название</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $org): ?>
                        <tr>
                            <td class="text-center"><?= Html::img($org->flag, ['style' => 'max-width: 100px; height: 20px']) ?></td>
                            <td><a href="#!state&id=<?= $org->id ?>"><?= Html::encode($org->name) ?></a></td>
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