<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\Powers,
    app\models\politics\constitution\articles\postsonly\Bills;

/* @var $this \yii\web\View */
/* @var $user \app\models\User */

?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box-group">
            <?php foreach ($user->posts as $post): ?>
            <?php 
                /* @var $powersBills Bills */
                $powersBills = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, Powers::BILLS); 
            ?>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?=Html::encode($post->name)?></h3>
                        <div class="box-tools pull-right">
                            <?=LinkCreator::stateLink($post->state)?>
                        </div>
                    </div>
                    <div class="box-body">
                        <h4><?=Yii::t('app', 'Bills powers')?>:</h4>
                        <ul>
                        <?php foreach ($powersBills->selected as /*$val =>*/ $name): ?>
                            <li><?=$name?></li>
                        <?php endforeach ?>
                        </ul>
                    </div>
                    <div class="box-footer">
                        <div class="btn-group">
                        <?php if ($powersBills->isSelected(Bills::CREATE)): ?>
                            <button id="new-bill-btn" class="btn btn-primary"><?=Yii::t('app', 'New bill')?></button>
                        <?php endif ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $('#new-bill-btn').click(function(){
        createAjaxModal(
            'work/new-bill-form',
            {postId:<?=$post->id?>},
            '<?=Yii::t('app', 'New bill')?>',
            '<button id="new-bill-confirm-btn" onclick="$(\'#new-bill-form\').yiiActiveForm(\'submitForm\')" class="btn btn-primary new-bill-confirm-btn" ><?=Yii::t('app', 'Create new bill')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Close')?></button>'
        );
    });
</script>
