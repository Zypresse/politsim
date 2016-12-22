<?php

use yii\helpers\Html,
    app\components\widgets\ElectionInfoWidget,
    app\models\politics\elections\Election;

/* @var $this yii\base\View */
/* @var $elections Election[] */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Next elections')?>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><?=Yii::t('app', 'Next elections')?></li>
    </ol>
</section>
<section class="content">
    <?php foreach ($elections as $election): ?>
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title"><?=Yii::t('app', 'Elections of agency post {0}', [Html::encode($election->whom->name)])?></h3>
        </div>
        <div class="box-body">
          <?= ElectionInfoWidget::widget(['election' => $election]) ?>
        </div>
    </div>
    <?php endforeach ?>
</section>
<script type="text/javascript">
    
    function sendElectionRequestModal() {
        createAjaxModal(
            'elections/send-request-form',
            {id: $(this).data('id')},
            '<?=Yii::t('app', 'Election request')?>',
            '<button class="btn btn-primary send-election-request-confirm-btn" data-id="'+$(this).data('id')+'"><?=Yii::t('app', 'Send request')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Close')?></button>'
        );
    }
    
    function sendElectionRequestConfirm() {
        json_request('elections/send-request', {id: $(this).data('id')});
    }
    
    $('.send-election-request-modal-btn').click(sendElectionRequestModal);
    $(document.body).on('click', '.send-election-request-confirm-btn', sendElectionRequestConfirm);
    
</script>