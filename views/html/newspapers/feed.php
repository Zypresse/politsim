<?php

use app\models\User,
    app\models\massmedia\Massmedia,
    app\models\massmedia\MassmediaPost,
    app\models\massmedia\MassmediaEditor,
    app\components\MyHtmlHelper,
    yii\helpers\Html,
    yii\helpers\ArrayHelper;

/* @var $newspaper Massmedia */
/* @var $user User */
/* @var $posts MassmediaPost[] */

$canDelete = false;
$canDeleteComments = false;

$rules = $newspaper->getEditorRules($user->id);
if ($rules) {
    $canDelete = $rules->isHavePermission(MassmediaEditor::RULE_DELETE_POSTS);
    $canDeleteComments = $rules->isHavePermission(MassmediaEditor::RULE_DELETE_COMMENTS);
}

?>
<section class="content">
    <div class="row">
         <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <span class="profile-user-img img-responsive img-circle" style="text-align: center; font-size: 50px">
                        <i class="fa fa-newspaper-o"></i>
                    </span>

                    <h3 class="profile-username text-center"><?=$newspaper->name?></h3>

                    <p class="text-muted text-center"><?=$newspaper->holding->htmlName?></p>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item text-center">
                            <b>Охват аудитории:</b><br><?=MyHtmlHelper::formateNumberword($newspaper->coverage, 'h')?> <i class="fa fa-user"></i>
                        </li>
                        <li class="list-group-item">
                            <b>Рейтинг</b> <span class="star pull-right"><?=$newspaper->rating?> <i class="fa fa-star"></i></span>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-info btn-block" onclick="load_page('newspaper',{id:<?=$newspaper->id?>})"><b>Подробнее</b></a>
                    <!--<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="box box-default">
                <div class="box-body">
                <?php foreach ($posts as $post): ?>
                    <div class="post clearfix">
                        <div class="user-block">  
                            <?php if ($canDelete): ?>
                            <span class="pull-right">
                                <a href="#" class="delete-post text-danger" data-post-id="<?=$post->id?>" >
                                    <i class="fa fa-times"></i>
                                </a>
                            </span>                  
                            <?php endif ?>
                            <h3 class="username">
                                <?php if ($post->rating > 0): ?>
                                <span class="text-success">
                                    <i class="fa fa-plus"></i> <?=$post->rating?>
                                </span>
                                <?php elseif ($post->rating < 0): ?>
                                <span class="text-danger">
                                    <i class="fa fa-minus"></i> <?=abs($post->rating)?>
                                </span>
                                <?php else: ?>
                                <span class="text-warning">
                                    0
                                </span>
                                <?php endif ?>
                                <?=$post->title?>
                            </h3>
                            <span class="description">
                                <span class="formatDate" data-unixtime="<?=$post->created?>"><?=date('d-m-Y',$post->created)?></span>
                                —
                                <?=$post->author->htmlName?>
                            </span>
                        </div>
                        <?=$post->text?>
                        <ul class="list-inline">
                            <li>
                                <?php
                                    $vote = $post->getUserVote($user);                                    
                                ?>
                                <div class="btn-group">
                                    <a href="#" data-direction="-1" data-post-id="<?=$post->id?>" class="vote btn btn-xs <?=$vote?'disabled '.($vote->direction < 0 ? 'btn-danger' : 'btn-default'):'btn-default'?> text-center" title="Мне не нравится">&nbsp;<i class="fa fa-thumbs-o-down margin-r-5"></i></a>
                                    <a href="#" data-direction="0" data-post-id="<?=$post->id?>" class="vote btn btn-xs <?=$vote?'disabled btn-default':'btn-default'?> text-center" title="Воздержаться"><i class="fa fa-minus"></i></a>
                                    <a href="#" data-direction="1" data-post-id="<?=$post->id?>" class="vote btn btn-xs <?=$vote?'disabled '.($vote->direction > 0 ? 'btn-success' : 'btn-default'):'btn-default'?> text-center" title="Мне нравится">&nbsp;<i class="fa fa-thumbs-o-up margin-r-5"></i></a>
                                </div>
                            </li>
                            <li class="pull-right">
                                <a href="#" class="link-black text-sm load-comments" data-post-id="<?=$post->id?>"><i class="fa fa-comments-o margin-r-5"></i> Комментарии (<span id="comments-counter-<?=$post->id?>"><?=$post->getComments()->count()?></span>)</a>
                            </li>
                        </ul>
                        <div id="comments-block-<?=$post->id?>" class="comments-block chat" style="margin-top:20px"></div>
                        <form class="form-horizontal send-post-comment" data-post-id="<?=$post->id?>" >
                            <?=Html::input('hidden', 'id', $post->id)?>
                            <div class="form-group margin-bottom-none">
                                <div class="col-sm-9">
                                    <input name="text" class="form-control input-sm text-input" placeholder="Написать комментарий">
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary pull-right btn-block btn-sm">Отправить</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(function(){
        $('.vote').click(function(){
            json_request('massmedia-post-vote',{id:$(this).data('postId'),direction:$(this).data('direction')});
        });
        
        <?php if ($canDelete): ?>
        $('.delete-post').click(function(){
            json_request('massmedia-post-delete',{id:$(this).data('postId')});
        });
        <?php endif ?>
            
        $('.send-post-comment').submit(function(){
            var post_id = $(this).data('postId');
            json_post_request('massmedia-post-comment',$(this).serializeObject(),true,false,function(){
                load_comments(post_id);
            });
            $(this).find('.text-input').val('');
            return false;
        });
        
        $('.load-comments').click(function(){
            load_comments($(this).data('postId'));
        });
        
        <?php if ($canDeleteComments): ?>
        $('.comments-block').on('click','.delete-comment',function(){
            var postId = $(this).data('postId'),
                userId = $(this).data('userId'),
                created = $(this).data('created');
            json_request('massmedia-post-comment-delete', {
                'postId':postId,
                'userId':userId,
                'created':created
            },true,false,function(){
                $('#comment-'+postId+'-'+userId+'-'+created).remove();
                $('#comments-counter-'+postId).text(parseInt($('#comments-counter-'+postId).text()) - 1);                
            });
        });
        <?php endif ?>
    });
    
    function load_comments(postId) {
        get_html('massmedia-post-comments',{id:postId},function(data){
            $('#comments-block-'+postId).html(data);
            prettyDates();
            <?php if (!$canDeleteComments): ?>
               $('.delete-comment').remove();
            <?php endif ?>
        });
    }
</script>