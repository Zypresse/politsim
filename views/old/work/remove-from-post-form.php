<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $targetPost app\models\politics\AgencyPost */

?>
<div class="box">
    <div class="box-header">
        <h4 class="box-title"><?=Yii::t('app', 'Remove from post «{0}»', [Html::encode($targetPost->name)])?></h4>
    </div>
    <div class="box-body">
        <div class="col-md-12">
            <?=Yii::t('app', 'Are you really wanna remove user {0} from this post?',[
                LinkCreator::userLink($targetPost->user),
            ])?>
        </div>
    </div>
</div>
<script type="text/javascript">
        
    $('#remove-from-post-confirm-btn').click(function(){
        json_request(
            'work/remove-from-post',
            {postId:<?=$post->id?>,targetPostId:<?=$targetPost->id?>},
            false, false, null, 'POST'
        );
    });

</script>