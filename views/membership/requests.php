<?php

use app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this \yii\web\View */
/* @var $user \app\models\User */
/* @var $requests \app\models\Membership[] */
/* @var $party \app\models\Party */

?>
<table class="table table-bordered table-condensed table-hover">
    <thead>
        
    </thead>
    <tbody>
        <?php foreach ($requests as $request):?>
        <tr id="accept-mebmership-request-tr<?=$request->userId?>">
            <td>
                <?=LinkCreator::userLink($request->user)?>
                <span class="star"><?=$request->user->fame?> <?=MyHtmlHelper::icon('star')?></span>
                <span class="heart"><?=$request->user->trust?> <?=MyHtmlHelper::icon('heart')?></span>
                <span class="chart_pie"><?=$request->user->success?> <?=MyHtmlHelper::icon('chart_pie')?></span>
            </td>
            <td>
                <button class="accept-mebmership-request-btn btn btn-success" data-user-id="<?=$request->userId?>" ><?=Yii::t('app', 'Accept')?></button>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
<script type="text/javascript">
    $('.accept-mebmership-request-btn').click(function(){
        var userId = $(this).data('userId');
        get_json('membership/accept', {userId:userId, partyId:<?=$party->id?>}, function(data){
            if (data && data.result === "ok") {
                $('#accept-mebmership-request-tr'+userId).remove();
            } else {
                console.error(data);
                show_custom_error("<?=Yii::t('app', 'Unknown error')?>");
            }
        });
    });
</script>