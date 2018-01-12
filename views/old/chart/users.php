<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $list app\models\User[] */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Users chart')?>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-th-list"></i> <?=Yii::t('app', 'Charts')?></li>
        <li class="active"><?=Yii::t('app', 'Users chart')?></li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <table id="chart_users" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th><?=Yii::t('app', 'User')?></th>
                        <th><?=Yii::t('app', 'Fame')?></th>
                        <th><?=Yii::t('app', 'Trust')?></th>
                        <th><?=Yii::t('app', 'Success')?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $user): ?>
                    <tr>
                        <td><?=LinkCreator::userLink($user)?></td>
                        <td>
                            <span class="star"><?=$user->fame?> <?=MyHtmlHelper::icon('star')?></span>
                        </td>
                        <td>
                            <span class="heart"><?=$user->trust?> <?=MyHtmlHelper::icon('heart')?></span>
                        </td>
                        <td>
                            <span class="chart_pie"><?=$user->success?> <?=MyHtmlHelper::icon('chart_pie')?></span>
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
    $("#chart_users").DataTable({
        ordering: false,
        language: datatable_language
    });
});

</script>