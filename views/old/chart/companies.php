<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $list app\models\economics\Company[] */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Companies chart')?>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-th-list"></i> <?=Yii::t('app', 'Charts')?></li>
        <li class="active"><?=Yii::t('app', 'Companies chart')?></li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <table id="chart_parties" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th><?=Yii::t('app', 'Company')?></th>
                        <th><?=Yii::t('app', 'State')?></th>
                        <th><?=Yii::t('app', 'Capitalization')?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $company): ?>
                    <tr>
                        <td><?=LinkCreator::companyLink($company)?></td>
                        <td><?=LinkCreator::stateLink($company->state)?></td>
                        <td>
                            <?=MyHtmlHelper::aboutNumber($company->capitalization)?>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script type="text/javascript">

$(function(){
    $("#chart_companies").DataTable({
        ordering: false,
        language: datatable_language
    });
});

</script>