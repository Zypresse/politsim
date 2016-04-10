<?php 

    use app\components\MyHtmlHelper;

foreach ($tweets as $i => $tweet) { ?>
 <div class="tweet <?php if ($i === count($tweets)-1) { ?>last<?php } ?>">
 <strong><a href="#" onclick="load_page('twitter',{'uid':<?=$tweet->uid?>})"><?=htmlspecialchars($tweet->user->name)?></a></strong> <span class="date"><?=date('d-M-Y H:i',$tweet->date)?></span>
 <?php if ($tweet->originalUser) { ?><p class="date">Репост от <a href="#" onclick="load_page('twitter',{'uid':<?=$tweet->original?>})"><?=htmlspecialchars($tweet->originalUser->name)?></a></p><?php } ?>
 <p><?=MyHtmlHelper::parseTwitterLinks($tweet->text)?></p>
 <p class="tweet-footer"><?php if ($tweet->uid !== $viewer_id && $tweet->original !== $viewer_id) { ?><a href="#" class="btn btn-default btn-xs repost" title="Репост" data-id="<?=$tweet->id?>"><i class="icon-repeat"></i></a><?php } else { ?><i class="icon-repeat"></i><?php } ?> <?=MyHtmlHelper::formateNumberword($tweet->retweets,'репостов','репост','репоста')?></p>
 </div>
<?php } ?>