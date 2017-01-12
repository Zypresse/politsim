<?php

use yii\helpers\Html;

/* @var $this yii\base\View */
/* @var $model app\models\politics\bills\Bill */
/* @var $post app\models\politics\AgencyPost */
/* @var $types array */

?>
<div class="box-group">
    <div class="box">
        <div class="box-header">
            <h4 class="box-title"><?=Yii::t('app', 'Basic bill types')?></h4>
        </div>
        <div class="box-body">
            <div class="btn-group">
            <?php foreach ($types as $id => $name): ?>
                <button class="btn btn-default btn-lg new-bill-type-btn" data-id="<?=$id?>">
                    <?=$name?>
                </button>
            <?php endforeach ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    $('.new-bill-type-btn').click(function(){
        load_modal(
            'work/new-bill-form',
            {postId:<?=$post->id?>, protoId:$(this).data('id')},
            'work-new-bill-form-modal'
        );
    });

</script>