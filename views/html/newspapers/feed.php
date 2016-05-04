<?php

use app\models\User,
    app\models\massmedia\Massmedia,
    app\models\massmedia\MassmediaPost,
    app\components\MyHtmlHelper,
    yii\helpers\Html,
    yii\helpers\ArrayHelper;

/* @var $newspaper Massmedia */
/* @var $user User */
/* @var $posts MassmediaPost[] */

?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h3><i class="fa fa-newspaper-o"></i> <?=$newspaper->htmlName?></h3>
            <div class="box box-default">
                <div class="box-body">
                <?php foreach ($posts as $post): ?>
                    <div class="post clearfix">
                        <div class="user-block">  
                            <span class="pull-right">
                                <span class="formatDate" data-unixtime="<?=$post->created?>"><?=date('d-m-Y',$post->created)?></span>
                            </span>                          
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
                                <a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Комментарии (5)</a>
                            </li>
                        </ul>
                        <form class="form-horizontal">
                            <div class="form-group margin-bottom-none">
                                <div class="col-sm-10">
                                    <input class="form-control input-sm" placeholder="Написать комментарий">
                                </div>
                                <div class="col-sm-2">
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
    });
</script>