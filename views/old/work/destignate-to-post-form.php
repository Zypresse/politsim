<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $targetPost app\models\politics\AgencyPost */

?>
<div class="box">
    <div class="box-header">
        <h4 class="box-title"><?=Yii::t('app', 'Destignation to post «{0}»', [Html::encode($targetPost->name)])?></h4>
    </div>
    <div id="box-body-search" class="box-body">
        <form action="" id="destignate-to-post-search-form">
            <div class="input-group">
                <input id="destignate-to-post-user-input" autofocus name="UserSearch[name]" class="form-control" placeholder="Введите имя..." type="text" value="" >
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-info btn-flat">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <table class="table table-bordered">
            <tbody id="destignate-to-post-user-list">
                <tr id="destignate-to-post-user-list-no-items">
                    <td class="text-center"><?=Yii::t('app', 'User not found')?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <input id="destignate-to-post-user-id" type="hidden">
    <div id="box-body-view" style="display: none">
        
    </div>
</div>
<script type="text/javascript">
    
    $('#destignate-to-post-user-input').focus();
    
    $('#destignate-to-post-search-form').submit(function(){
        var $list = $('#destignate-to-post-user-list');
        $list.empty();
        get_json('user/find', {name:$('#destignate-to-post-user-input').val(), stateId:<?=$post->stateId?>}, function(data){
            if (data.result && data.result.length) {
                for (var i = 0, l = data.result.length; i<l; i++) {
                    var user = data.result[i];
                    $list.append('<tr><td>'+user.name+'</td><td>'+
                        '<span class="star" >'+user.fame+" <?= MyHtmlHelper::icon('star') ?></span>"+
                        '<span class="heart" >'+user.trust+" <?= MyHtmlHelper::icon('heart') ?></span>"+
                        '<span class="chart_pie" >'+user.success+" <?= MyHtmlHelper::icon('chart_pie') ?></span>"+
                    '</td><td><button data-id="'+user.id+'" class="btn btn-default btn-xs btn-user-select"><?=Yii::t('app', 'Select')?></button></td></tr>');
                }
            } else {
                $list.html('<tr id="destignate-to-post-user-list-no-items"><td class="text-center"><?=Yii::t('app', 'User not found')?></td></tr>');
            }
        });
        return false;
    });
    
    $('#destignate-to-post-user-list').on('click', '.btn-user-select', function(){
        $('#box-body-search').hide();
        $('#box-body-view').html('<div class="col-md-12 text-center"><?=Yii::t('app', 'Loading...')?></div>').show();
        $('#destignate-to-post-user-id').val($(this).data('id'));
        get_json('user/info', {id:$(this).data('id')}, function(data){
            $('#box-body-view').html('<div class="col-md-12"><strong><?=Yii::t('app', 'Candidat:')?></strong> '+data.result.name+'</div>');
            $('#destignate-to-post-confirm-btn').removeAttr('disabled');
        });
    });
    
    $('#destignate-to-post-confirm-btn').click(function(){
        json_request(
            'work/destignate-to-post',
            {postId:<?=$post->id?>,targetPostId:<?=$targetPost->id?>,userId:$('#destignate-to-post-user-id').val()},
            false, false, null, 'POST'
        );
    });

</script>