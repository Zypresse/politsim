<?php

use yii\helpers\Html,
    app\components\LinkCreator;

/* @var $this \yii\web\View */
/* @var $user \app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Parties')?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::userLink($user)?></li>
        <li class="active"><i class="fa fa-group"></i> <?=Yii::t('app', 'Parties')?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">
                    <h3><?=Yii::t('app', 'You are have not parties membership')?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <?php if (count($user->states)): ?>
                <?php foreach ($user->states as $state): ?>
                <div class="box">
                    <div class="box-title">
                        <h4><?=LinkCreator::stateLink($state)?></h4>
                    </div>
                    <div class="box-body">
                        <div class="btn-group">
                            <button class="create-party-btn btn btn-primary" data-state-id="<?=$state->id?>" ><i class="fa fa-plus"></i> <?=Yii::t('app', 'Create party')?></button>
                        </div>
                    </div>
                </div>
                <?php endforeach ?>
            <?php else: ?>
            <div class="box">
                <div class="box-body">
                    <?=Yii::t('app', 'You have not citizehship and you can not create parties')?>
                </div>
            </div>
            <?php endif ?>
        </div>
    </div>
</section>

<script type="text/javascript">

    $('.create-party-btn').click(function(){
        createAjaxModal('party/create-form', {stateId: $(this).data('stateId')}, '<?=Yii::t('app', 'Party creation')?>', '<button class="btn btn-primary" onclick="json_request(\'party/create\', $(\'#party-create-form\').serialize())" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Create')?></button> <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Close')?></button>');
    });
    
</script>
