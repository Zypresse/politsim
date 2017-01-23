<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\models\politics\constitution\articles\postsonly\powers\Bills;

/* @var $this \yii\web\View */
/* @var $user \app\models\User */
/* @var $post \app\models\politics\AgencyPost */
/* @var $postsDestignated \app\models\politics\AgencyPost[] */

?>
<div class="box">
    <div class="box-header">
        <h4 class="box-title"><?= Yii::t('app', 'Destignation posts') ?></h4>
    </div>
    <div class="box-body">
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th><?= Yii::t('app', 'Agency post') ?></th>
                    <th><?= Yii::t('app', 'User') ?></th>
                    <th><?= Yii::t('app', 'Action') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($postsDestignated as $currentPost): ?>
                    <tr>
                        <td>
                            <?=Html::encode($currentPost->name)?>
                        </td>
                        <td>
                            <?= ($currentPost->user) ? LinkCreator::userLink($currentPost->user) : Yii::t('yii', '(not set)') ?>
                        </td>
                        <td>
                            <?php if ($currentPost->user): ?>
                                <?=Html::button(Yii::t('app', 'Remove from post'), ['class' => 'btn btn-danger btn-remove-from-post', 'data-target-post-id' => $currentPost->id, 'data-post-id' => $post->id])?>
                            <?php else: ?>
                                <?=Html::button(Yii::t('app', 'Destignate to post'), ['class' => 'btn btn-primary btn-destignate-to-post', 'data-target-post-id' => $currentPost->id, 'data-post-id' => $post->id])?>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    $('.btn-remove-from-post').click(function(){
        createAjaxModal(
            'work/remove-from-post-form',
            {postId:$(this).data('postId'), targetPostId:$(this).data('targetPostId')},
            '<?=Yii::t('app', 'Remove from post')?>',
            '<button id="remove-from-post-confirm-btn" class="btn btn-warning remove-from-post-confirm-btn" ><?=Yii::t('app', 'Remove from post')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Close')?></button>'
        );
    });
    $('.btn-destignate-to-post').click(function(){
        createAjaxModal(
            'work/destignate-to-post-form',
            {postId:$(this).data('postId'), targetPostId:$(this).data('targetPostId')},
            '<?=Yii::t('app', 'Destignate to post')?>',
            '<button id="destignate-to-post-confirm-btn" disabled="disabled" class="btn btn-primary destignate-to-post-confirm-btn" ><?=Yii::t('app', 'Destignate to post')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Close')?></button>'
        );
    });
</script>
