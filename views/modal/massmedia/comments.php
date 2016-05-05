<?php

use app\models\massmedia\MassmediaPostComment;

/* @var $comments MassmediaPostComment[] */

?>

<?php foreach ($comments as $comment): ?>
<div class="item" id="comment-<?=$comment->massmediaPostId?>-<?=$comment->userId?>-<?=$comment->created?>">
    <img src="<?=$comment->user->photo?>" alt="user image" >

    <p class="message">
        <a href="#" class="delete-comment text-danger pull-right" style="margin-left: 10px" data-post-id="<?=$comment->massmediaPostId?>" data-user-id="<?=$comment->userId?>" data-created="<?=$comment->created?>" >
            <i class="fa fa-times"></i>
        </a>
        <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <span class="prettyDate" data-unixtime="<?=$comment->created?>"></span></small>            
        <a href="#" class="name" onclick="load_page('profile', {id:<?=$comment->user->id?>})">
            <?=$comment->user->name?>
        </a>
        <?=$comment->text?>
    </p>
</div>
<?php endforeach ?>
<script>
    $(function(){
        <?php if (isset($comment)): ?>
        $('#comments-counter-'+<?=$comment->massmediaPostId?>).text(<?=count($comments)?>);
        <?php endif ?>
    });
</script>