<?php 
use app\components\MyHtmlHelper;

$own = ($viewer_id === $user->id);
?>
        <div style="display:none" class="modal fade" id="tweet_about_human" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel">Написать пост о другом человеке</h3>
                    </div>
                    <div id="tweet_about_human_body" class="modal-body">
                        Загрузка...
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
                        <button class="btn btn-primary" id="send_tweet_human">Отправить</button>
                    </div>
                </div>
            </div>
        </div>
        <?php if (!$user->twitter_nickname && $own) { ?>
            <div style="display:none" class="modal fade" id="set_twitter_nickname" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel2">У вас не установлен ник в соц. сетях!</h3>
                        </div>
                        <div id="set_twitter_nickname_body" class="modal-body">
                            <p>Введите ваш никнейм, по нему о вас смогут писать другие пользователи</p>
                            <p><input type="text" id="new_nickname" ></p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
                            <button class="btn btn-success" id="save_nickname" >Сохранить</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
<section class="content">
    <div class="row">
            <div class="col-md-6">
            <?php if (!$own): ?>
            <button class="btn btn-block btn-danger" onclick="load_page('twitter')"><i class="fa fa-chevron-left"></i> Вернуться свой профиль</button>
            <?php endif ?>
                <div class="twPc-div">
                <a class="twPc-bg twPc-block" href="#"></a>

	<div>
            <?php /*
		<div class="twPc-button">
            <!-- Twitter Button | you can get from: https://about.twitter.com/tr/resources/buttons#follow -->
            <a href="https://twitter.com/mertskaplan" class="twitter-follow-button" data-show-count="false" data-size="large" data-show-screen-name="false" data-dnt="true">Follow @mertskaplan</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
            <!-- Twitter Button -->   
		</div>
                */ ?>
                <a href="#" title="<?=$user->name?>" onclick="load_page('profile', {'uid':<?= $user->id ?>});" class="twPc-avatarLink">
                    <img alt="Mert Salih Kaplan" src="<?= $user->photo ?>" class="twPc-avatarImg">
		</a>

		<div class="twPc-divUser">
			<div class="twPc-divName">
				<a href="#" onclick="load_page('profile', {'uid':<?= $user->id ?>});" ><?=$user->name?></a>
			</div>
                        <?php if ($user->twitter_nickname): ?>
			<span>
				<a href="#" onclick="load_page('twitter', {'uid':<?= $user->id ?>});" >@<span><?=$user->twitter_nickname?></span></a>
			</span>
                        <?php endif ?>
		</div>

		<div class="twPc-divStats">
			<ul class="twPc-Arrange">
				<li class="twPc-ArrangeSizeFit">
                                    <a href="#" title="<?=MyHtmlHelper::formateNumberword($user->getTweetsCount(),'постов','пост','поста')?>">
                                        <span class="twPc-StatLabel twPc-block"><i class="fa fa-twitter"></i> Посты</span>
                                        <span class="twPc-StatValue"><?=$user->getTweetsCount()?></span>
                                    </a>
				</li>
				<li class="twPc-ArrangeSizeFit">
                                    <a href="#" title="">
                                        <span class="twPc-StatLabel twPc-block"><i class="fa fa-map-marker"></i> Местоположение</span>
                                        <span class="twPc-StatValue"><?=$user->region ? $user->region->city : ''?></span>
                                    </a>
				</li>
				<li class="twPc-ArrangeSizeFit">
					<a href="#" title="<?=MyHtmlHelper::formateNumberword($user->getTwitterSubscribersCount(),'подписчиков','подписчик','подписчика')?>">
						<span class="twPc-StatLabel twPc-block"><i class="fa fa-user"></i> Подписчики</span>
						<span class="twPc-StatValue"><?=$user->getTwitterSubscribersCount()?></span>
					</a>
				</li>
			</ul>
		</div>
        </div>
                </div>
                <?php if ($own): ?>
                    <h5>Выберите, о чём написать:</h5>
                    <p>
                        <button class="btn btn-default btn-sm" onclick="load_modal('tweet-about-human', {}, 'tweet_about_human', 'tweet_about_human_body');">О человеке</button>
<!--                        <button class="btn btn-default" disabled="disabled">О событии</button>
                        <button class="btn btn-default" disabled="disabled">О погоде</button>-->
                    </p>
                    <h5>Новый пост:</h5>
                    <textarea autofocus id="new_message" name="new_message"
                              placeholder="Введите ваше сообщение" rows="5" class="socnet-textarea"></textarea>
                    <span class="pull-right" id="symbols_count">140 символов осталось</span>
                    <button class="btn btn-primary btn-xs" type="submit" onclick="if ($('#new_message').val())
                                    json_request('tweet', {'text': $('#new_message').val()})">Отправить</button>
                    <br>
                <?php endif ?>
                <h4>Последние посты <small>(всего <?= $user->getTweetsCount() ?>)</small></h4>
                <?php foreach ($tweets as $i => $tweet) { ?>
                    <div class="tweet <?php if ($i === count($tweets) - 1) { ?>last<?php } ?>">
                        <strong><a href="#" onclick="load_page('twitter', {'uid':<?= $user->id ?>})"><?= htmlspecialchars($user->name) ?></a></strong> <span class="date prettyDate" data-unixtime="<?= $tweet->date ?>"><?= date('d-M-Y H:i', $tweet->date) ?></span>
                        <?php if ($tweet->originalUser) { ?><p class="date">Репост от <a href="#" onclick="load_page('twitter', {'uid':<?= $tweet->original ?>})"><?= htmlspecialchars($tweet->originalUser->name) ?></a></p><?php } ?>
                        <p><?= MyHtmlHelper::parseTwitterLinks($tweet->text) ?></p>
                        <p class="tweet-footer"><?php if ($tweet->uid !== $viewer_id && !$own && $tweet->original !== $viewer_id) { ?><a href="#" class="btn btn-xs btn-default repost" data-id="<?= $tweet->id ?>" title="Репост"><i class="fa fa-repeat"></i></a><?php } else { ?><i class="fa fa-repeat"></i><?php } ?> <?= MyHtmlHelper::formateNumberword($tweet->retweets, 'репостов', 'репост', 'репоста') ?> <?php if ($own) { ?><button class="btn btn-danger btn-sm delete delete_tweet" title="Удалить" data-id="<?= $tweet->id ?>" >X</button><?php } ?></p>
                    </div>
                <?php } ?>
                <button class="btn btn-block btn-default" id="update_user_feed" data-time="<?= $timeFeedGenerated ?>" data-offset="3" data-uid="<?= $user->id ?>" >Далее</button>
            </div>
            <div class="col-md-6" id="twitter_popular_feed">
                <h4>Популярные посты</h4>
                <?php foreach ($feed as $i => $tweet) { ?>
                    <div class="tweet <?php if ($i === count($feed) - 1) { ?>last<?php } ?>">
                        <a href="#" onclick="load_page('twitter', {'uid':<?= $tweet->uid ?>})"><strong><?= htmlspecialchars($tweet->user->name) ?></strong><?php if ($tweet->user->twitter_nickname): ?> @<?= $tweet->user->twitter_nickname ?><?php endif ?></a> <span class="date prettyDate" data-unixtime="<?= $tweet->date ?>"><?= date('d-M-Y H:i', $tweet->date) ?></span>
                        <?php if ($tweet->originalUser) { ?><p class="date">Репост от <a href="#" onclick="load_page('twitter', {'uid':<?= $tweet->original ?>})"><?= htmlspecialchars($tweet->originalUser->name) ?></a></p><?php } ?>
                        <p><?= MyHtmlHelper::parseTwitterLinks($tweet->text) ?></p>
                        <p class="tweet-footer"><?php if ($tweet->uid !== $viewer_id && $tweet->original !== $viewer_id) { ?><a href="#" class="btn btn-xs btn-default repost" title="Репост" data-id="<?= $tweet->id ?>"><i class="fa fa-repeat"></i></a><?php } else { ?><i class="fa fa-repeat"></i><?php } ?> <?= MyHtmlHelper::formateNumberword($tweet->retweets, 'репостов', 'репост', 'репоста') ?></p>
                    </div>
                <?php } ?>
                <button class="btn btn-block btn-default" id="update_feed" data-time="<?= $timeFeedGenerated ?>" data-offset="5" >Далее</button>
            </div>
    </div>
</section>

        <script>
            function repost(id) {
                if (confirm('Вы действительно хотите сделать репост?')) {
                    json_request('retweet', {'id': id});
                }
                return false;
            }
            function bind_twitter_events() {

                $(".repost").off("click").on("click", function () {
                    if (confirm('Вы действительно хотите сделать репост?')) {
                        json_request('retweet', {'id': $(this).data('id')});
                    }
                    return false;
                });
                $(".delete_tweet").off("click").on("click", function () {
                    if (confirm('Вы действительно хотите удалить пост?')) {
                        json_request('delete-tweet', {'id': $(this).data('id')});
                    }
                    return false;
                });

            }

            $(function () {
<?php if ($own) { ?>
                    $('#set_twitter_nickname').modal();

                    $('#save_nickname').click(function () {
                        var nick = $('#new_nickname').val().toLowerCase();
                        if (nick && !(/[^qwertyuiopasdfghjklzxcvbnm0123456789]/.test(nick)) && nick.length < 20 && nick.length > 3)
                            json_request('set-twitter-nickname', {'nick': nick});
                        else
                            alert('Ник должен содержать только латинские буквы и быть от 4 до 20 знаков в длину');
                    });

<?php } ?>

                $('#update_feed').click(function () {
                    $(this).attr("disabled", "disabled");
                    var offset = parseInt($(this).data('offset'));
                    get_html('get-twitter-feed', {'time': $(this).data('time'), 'offset': offset}, function (rows) {

                        $('#twitter_popular_feed .last').removeClass('last');
                        $('#twitter_popular_feed').append(rows);
                        $('#twitter_popular_feed').append($('#update_feed'));
                        $('#update_feed').data('offset', offset + 5).removeAttr("disabled");
                        bind_twitter_events();
                    });
                });

                $('#update_user_feed').click(function () {
                    $(this).attr("disabled", "disabled");
                    var offset = parseInt($(this).data('offset'));
                    get_html('get-twitter-feed', {'time': $(this).data('time'), 'offset': offset, 'uid': $(this).data('uid')}, function (rows) {

                        $('#twitter_user_feed .last').removeClass('last');
                        $('#twitter_user_feed').append(rows);
                        $('#twitter_user_feed').append($('#update_user_feed'));
                        $('#update_user_feed').data('offset', offset + 5).removeAttr("disabled");
                        bind_twitter_events();
                    });
                });

                $('#new_message').keydown(function (e) {
                    switch (e.keyCode) {
                        case 13:
                        case 8:
                        case 9:
                        case 46:
                        case 37:
                        case 38:
                        case 39:
                        case 40:
                            return true;
                    }
                    var c = ($(this).val().length > 110) ? '<span style="color:red">' : '<span>'
                    if ($(this).val().length < 140)
                        $('#symbols_count').html(c + (139 - $(this).val().length) + ' символов осталось</span>');
                    return ($(this).val().length < 140)
                }).keyup(function () {
                    var c = ($(this).val().length > 110) ? '<span style="color:red">' : '<span>'
                    $('#symbols_count').html(c + (140 - $(this).val().length) + ' символов осталось</span>');
                });

                bind_twitter_events();
            });
        </script>
    