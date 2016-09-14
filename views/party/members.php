<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator,
    app\models\Party,
    app\models\PartyPost;

/* @var $this yii\base\View */
/* @var $party app\models\Party */
/* @var $members app\models\User */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Members list')?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::partyLink($party)?></li>
        <li class="active"><?=Yii::t('app', 'Members list')?></li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <table id="members_list" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th><?=Yii::t('app', 'Name')?></th>
                        <th><?=Yii::t('app', 'Attributes')?></th>
                        <th><?=Yii::t('app', 'Posts')?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td><?=LinkCreator::userLink($member)?></td>
                        <td>
                            <span class="star" ><?= $member->fame ?> <?= MyHtmlHelper::icon('star') ?></span>
                            <span class="heart" ><?= $member->trust?> <?= MyHtmlHelper::icon('heart') ?></span>
                            <span class="chart_pie" ><?= $member->success ?> <?= MyHtmlHelper::icon('chart_pie') ?></span>
                        </td>
                        <td>
                            <?php
                                $postNames = [];
                                foreach ($member->partyPosts as $post) {
                                    if ($post->partyId == $party->id) {
                                        $postNames[] = Html::encode($post->name);
                                    }
                                }
                            ?>
                            <?=implode(', ', $postNames)?>
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
    $("#members_list").DataTable({
        ordering: false,
        language: datatable_language
    });
});

</script>