<?php

/**
 * @global \app\models\User $user;
 */
use app\components\MyHtmlHelper,
    app\models\Org,
    app\models\ElectOrgLeaderRequest;
?>

<div class="container">
    <div class="row">
        <div class="col-md-2">
            <div class="avarar-container box" >
                <div class="box-content">
                    <img src="<?= $party->image ?>" alt="<?= $party->name ?>" class="img-polaroid">
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <h1><?= htmlspecialchars($party->name) ?> <small>(<?= htmlspecialchars($party->short_name) ?>)</small></h1>
            <p>Партия зарегистрирована в государстве <?=$party->state->getHtmlName()?></p>
            <p><strong>Идеология</strong>: <?= htmlspecialchars($party->ideologyInfo->name) ?></p>
            <p><strong>Лидер партии</strong>:
                <? if ($party->leaderInfo) { ?>
                    <?=$party->leaderInfo->getHtmlName()?>
                    <span class="star"><?= $party->leaderInfo->star ?> <?= MyHtmlHelper::icon('star') ?></span>
                    <span class="heart"><?= $party->leaderInfo->heart ?> <?= MyHtmlHelper::icon('heart') ?></span>
                    <span class="chart_pie"><?= $party->leaderInfo->chart_pie ?> <?= MyHtmlHelper::icon('chart_pie') ?></span>
                <? } else { ?>
                    не назначен
                <? } ?>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <strong>Список членов партии:</strong> <input type="button" class="btn btn-default btn-xs" id="members_show" value="Показать">
            <ul id="members_list" >
                <? foreach ($party->members as $player) { ?>
                    <li>
                        <?=$player->getHtmlName()?>
                        <span class="star"><?= $player->star ?> <?= MyHtmlHelper::icon('star') ?></span>
                        <span class="heart"><?= $player->heart ?> <?= MyHtmlHelper::icon('heart') ?></span>
                        <span class="chart_pie"><?= $player->chart_pie ?> <?= MyHtmlHelper::icon('chart_pie') ?></span>
                    </li>
                <? } ?>
            </ul>
            <script type="text/javascript">
                $('#members_show').click(function () {
                    if ($(this).val() === 'Показать') {
                        $(this).val('Скрыть');
                        $('#members_list').slideDown();
                    } else {
                        $(this).val('Показать');
                        $('#members_list').slideUp();
                    }
                })
            </script>

            <h3>Действия</h3>
            <div class="btn-toolbar">
                <?
                if ($user->isPartyLeader() && $user->party_id === $party->id) {
                    $emptyPosts = [];
                    if ($user->state && $user->state->legislatureOrg)
                        foreach ($user->state->legislatureOrg->posts as $post) {
                            if ($post->party_reserve === $user->party_id && is_null($post->user)) {
                                $emptyPosts[] = $post;
                            }
                        }

                    $isNeedRequestForSpeaker = $user->state && $user->state->legislatureOrg && ($user->state->legislatureOrg->leader_dest === Org::DEST_ORG_VOTE && is_null($user->state->legislatureOrg->leader->user) && $user->party->isParlamentarian() );
                    if ($isNeedRequestForSpeaker) {
                        $req = ElectOrgLeaderRequest::find()->where(['party_id' => $user->party_id, 'org_id' => $user->state->legislature])->one();
                        if ($req)
                            $isNeedRequestForSpeaker = FALSE;
                    }

                    if (count($emptyPosts))
                        echo "<p style='color:red'>Есть свободные посты в правительстве: " . MyHtmlHelper::formateNumberword(count($emptyPosts), "зарезервированных должностей", "зарезервированная должность", "зарезервированные должности") . "</p>";
                    if ($isNeedRequestForSpeaker)
                        echo "<p style='color:red'>Необходимо подать заявку на пост {$user->state->legislatureOrg->leader->name}</p>";
                    ?>
                    <div class="btn-group">
                        <button class="btn btn-small dropdown-toggle btn-lightblue" data-toggle="dropdown">
                            Управление <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#" onclick="rename_party(<?= $party->id ?>)" >Переименовать партию</a></li>
                            <li><a href="#" onclick="change_party_logo(<?= $party->id ?>)" >Сменить эмблему партии</a></li>
                            <? if (count($emptyPosts)) { ?><li><a href="#" onclick="$('#party-reserve-post-set').modal()">Назначить на зарезервированный пост</a></li><? } ?>
                            <? if ($isNeedRequestForSpeaker) { ?><li><a href="#" onclick="$('#party-request-to-speaker-elections').modal()">Подать заявку от партии на пост <?= $user->state->legislatureOrg->leader->name ?></a></li><? } ?>
                        </ul>
                    </div>
                    <? if (count($emptyPosts)) { ?>
                        <div style="display:none" class="modal fade" id="party-reserve-post-set" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h3 id="myModalLabel">Назначение на пост</h3>
                                    </div>

                                    <div id="party-reserve-post-set_body" class="modal-body">
                                        <form class="well form-horizontal">
                                            <div class="control-group">
                                                <label class="control-label" for="#prps_post_id">Пост</label>
                                                <div class="controls">
                                                    <select id="prps_post_id">
                                                        <? foreach ($emptyPosts as $post) { ?>
                                                            <option value="<?= $post->id ?>"><?= $post->name ?></option>
                                                        <? } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="#prps_user_id">Человек</label>
                                                <div class="controls">
                                                    <select id="prps_user_id">
                                                        <? foreach ($party->members as $member) {
                                                            if (!$member->post_id) {
                                                                ?>
                                                                <option value="<?= $member->id ?>"><?= $member->name ?></option>
                                                            <? }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>   
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="party_reserve_post_set()">Назначить</button>
                                        <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
                                    </div>
                                </div>
                            </div>
                        </div>
    <? } ?>
    <? if ($isNeedRequestForSpeaker) { ?>
                        <div style="display:none" class="modal fade" id="party-request-to-speaker-elections" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h3 id="myModalLabel">Заявка на выборы на пост <?= $user->state->legislatureOrg->leader->name ?></h3>
                                    </div>

                                    <div id="party-reserve-post-set_body" class="modal-body">
                                        <form class="well form-horizontal">
                                            <div class="control-group">
                                                <label class="control-label" for="#user_id">Человек</label>
                                                <div class="controls">
                                                    <select id="pesr_user_id">
                                                        <? foreach ($party->members as $member) {
                                                            if (is_null($member->post) || $member->post->org_id === $user->state->legislature) {
                                                                ?>
                                                                <option value="<?= $member->id ?>"><?= $member->name ?></option>
            <? }
        }
        ?>
                                                    </select>
                                                </div>
                                            </div>   
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="party_elect_speaker_request()">Подать заявку</button>
                                        <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
                                    </div>
                                </div>
                            </div>
                        </div>
    <? } ?>
                    <script type="text/javascript">

                        function rename_party(id) {
                            var name = prompt('Введите новое название для партии');
                            var short_name = prompt('Введите новое короткое название для партии');
                            if (name != "null" && name && short_name) {
                                json_request('rename-party', {'id': id, 'name': name, 'short_name': short_name});
                            }
                        }
                        function change_party_logo(id) {
                            var image = prompt('Введите ссылку на новый логотип для партии');
                            if (image != "null" && image) {
                                json_request('change-party-logo', {'id': id, 'image': image});
                            }
                        }
                        function party_reserve_post_set() {
                            json_request('party-reserve-set-post', {'post_id': $('#prps_post_id').val(), 'uid': $('#prps_user_id').val()});
                        }
                        function party_elect_speaker_request() {
                            json_request('party-elect-speaker-request', {'org_id':<?= $user->state->legislature ?>, 'uid': $('#pesr_user_id').val()});
                        }

                    </script>
<? } ?>

                    <? if ($party->id === $user->party_id) { ?>

                    <div class="btn-group">
                        <button class="btn btn-sm dropdown-toggle btn-red" onclick="if (confirm('Вы действительно хотите выйти из партии?')) {
                                        json_request('leave-party', {});
                                    }">
                            Выйти из партии
                        </button>
                    </div>

<? } elseif (!$user->party_id && $user->state_id === $party->state_id) { ?>

                    <div class="btn-group">
                        <button class="btn btn-sm dropdown-info btn-green" onclick="json_request('join-party', {'party_id':<?= $party->id ?>})">
                            Вступить в партию
                        </button>
                    </div>

                </div>
<? } ?>
        </div>
    </div>
</div>