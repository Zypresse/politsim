<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $user app\models\User */
/* @var $viewer app\models\User */
/* @var $shares app\models\economics\Resource[] */

$isOwner = $user->id == $viewer->id;

?>
<section class="content-header">
    <h1>
        <?=$isOwner ? Yii::t('app', 'Your business') : Yii::t('app', '{0} business', [LinkCreator::userLink($user)])?>
    </h1>
    <ol class="breadcrumb">
        <li><?= LinkCreator::userLink($user)?></li>
        <li class="active"><?=Yii::t('app', 'Business')?></li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><?=Yii::t('app', 'Shares')?></h3>
        </div>
        <div class="box-body">
            <table id="shares_list" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th><?=Yii::t('app', 'Share')?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($shares as $share): ?>
                    <tr>
                        <td>
                            <pre>
                                <?php var_dump($share) ?>
                            </pre>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
