<?php

use app\components\MyHtmlHelper,
    app\models\Org,
    app\models\bills\Bill,
    app\models\bills\proto\BillProto,
    app\components\widgets\BillListWidget,
    yii\helpers\Html;

$gft = null;
?>

<div class="container">
    <div class="row">
        <div style="display:none;" class="modal fade" id="naznach" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width: 800px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabelNaznach">Назначение на должность</h3>
                    </div>
                    <div id="naznach_body" class="modal-body">
                        <p>Загрузка…</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
                        <!--<button class="btn btn-green">Save changes</button>-->
                    </div>
                </div>
            </div>
        </div>
        <h1>Личный кабинет</h1>
        <p>Вы занимаете должность &laquo;<?= htmlspecialchars($user->post->name) ?>&raquo; в организации &laquo;<a href="#" onclick="load_page('org-info', {'id':<?= $user->post->org_id ?>});"><?= htmlspecialchars($user->post->org->name) ?></a>&raquo;
        <? if ($user->post->balance) { ?><p>На данной должности вам доступен бюджет в размере <?= number_format($user->post->balance, 0, '', ' ') ?> <?= MyHtmlHelper::icon('money') ?></p><? } ?>
        <? if ($user->post_id === $user->post->org->leader_post) { ?><p>Вы — лидер организации &laquo;<a href="#" onclick="load_page('org-info', {'id':<?= $user->post->org_id ?>});"><?= htmlspecialchars($user->post->org->name) ?></a>&raquo;<? if ($user->post->org->leader_can_create_posts) { ?> и можете создавать новые должности в ней<? } ?>.</p>
            <h3>Подчинённые</h3>
            <p>
                <strong>Список членов организации:</strong> <input type="button" class="btn btn-xs btn-default" id="posts_show" value="Показать"></p>
            <table id="posts_list" class="table" >
                <? foreach ($user->post->org->posts as $player) { ?>
                    <tr><td><strong><?= htmlspecialchars($player->name) ?></strong> <? if ($player->can_delete) { ?>
                                <button class="btn btn-red" onclick="delete_post(<?= $player->id ?>)" style="float:right;">Удалить</button>
                                <script type="text/javascript">
                                    function delete_post(id) {
                                        if (confirm('Вы действительно хотите удалить эту должность?')) {
                                            json_request('delete-post', {'id': id});
                                        }
                                    }
                                </script>
                            <? } ?></td><td>
                            <? if ($player->user) { ?>
                                <a href="#" onclick="load_page('profile', {'uid':<?= $player->user->id ?>})"><img src="<?= $player->user->photo ?>" alt="" style="width:32px;height:32px;"></a>
                                <a href="#" onclick="load_page('profile', {'uid':<?= $player->user->id ?>})"><?= htmlspecialchars($player->user->name) ?></a>
                                (<? if ($player->user->party) { ?><a href="#" onclick="load_page('party-info', {'id':<?= $player->user->party_id ?>});"><?= htmlspecialchars($player->user->party->short_name) ?></a><? } else { ?><? if ($player->user->sex === 1) { ?>Беспартийная<? } else { ?>Беспартийный<? } ?><? } ?>)
                                <span class="star"><?= $player->user->star ?> <?= MyHtmlHelper::icon('star') ?></span>
                                <span class="heart"><?= $player->user->heart ?> <?= MyHtmlHelper::icon('heart') ?></span>
                                <span class="chart_pie"><?= $player->user->chart_pie ?> <?= MyHtmlHelper::icon('chart_pie') ?></span>

                                <? if ($user->post->org->dest === 'dest_by_leader' && $player->id !== $user->post->org->leader_post) { ?>
                                    <button class="btn btn-red" onclick="drop_from_post(<?= $player->id ?>)">Сместить с поста</button>
                                    <script type="text/javascript">
                                        function drop_from_post(id) {
                                            if (confirm('Вы действительно хотите сместить этого человека с поста?')) {
                                                json_request('drop-from-post', {'id': id});
                                            }
                                        }
                                    </script>
                                <? } ?>
                            <? } else { ?>
                                <? if ($user->post->org->dest === 'dest_by_leader') { ?>
                                    <button class="btn btn-green" onclick="naznach(<?= $player->id ?>)">Назначить</button>
                                <? } else { ?>
                                    Не назначен
                                <? } ?>
                            <? } ?></td></tr>
                <? } ?>
            </table>
            <script type="text/javascript">
                $('#posts_show').click(function () {
                    if ($(this).val() === 'Показать') {
                        $(this).val('Скрыть');
                        $('#posts_list').slideDown();
                    } else {
                        $(this).val('Показать');
                        $('#posts_list').slideUp();
                    }
                });
            </script>
            <?
        } else {
            // Вы НЕ лидер организации
            ?>

            <? if ($user->post->org->leader_dest === Org::DEST_ORG_VOTE) { ?>
                <h4>Заявки на пост <?= $user->post->org->leader->name ?>:</h4><?
                if (count($user->post->org->speakerRequests)) {
                    ?><dl><?
                        foreach ($user->post->org->speakerRequests as $request) {
                            ?>
                            <dt><?= $request->candidat->name ?> (<?= Html::a($request->party->name, '#', ['onclick' => 'load_page("party_info",{"id":' . $request->party_id . '})']) ?>)</dt>     
                            <dd>Поддержало <strong><?= MyHtmlHelper::formateNumberword($request->getVotesCount(), 'голосов', 'голос', 'голоса') ?></strong> 
                                <? if ($user->post->org->isAllreadySpeakerVoted($user->post_id)) { ?>

                                <? } else { ?>
                                    <button class="btn btn-sm btn-default" onclick="json_request('vote-about-org-leader', {'request_id':<?= $request->id ?>})">Проголосовать</button>
                                <? } ?>
                            </dd>
                            <?
                        }
                        ?></dl><?
                } else {
                    echo "<p>Ни одна партия ещё не подала заявок</p>";
                }
                ?>
            <? } ?>

        <? } ?>
        <? if ($user->isStateLeader() && $user->state->executiveOrg->leader_can_make_dicktator_bills) { ?>
            <p>Вы можете принимать законы единолично</p>
        <? } ?>
        <? if ($user->isStateLeader() && $user->state->leader_can_drop_legislature && $user->state->legislatureOrg) { ?>
            <p>Вы можете распустить организацию «<a href="#" onclick="load_page('org-info', {'id':<?= $user->state->legislature ?>});"><?= $user->state->legislatureOrg->name ?></a>»</p>
        <? } ?>
        <? if ($user->post->org->can_vote_for_bills) { ?>
            <p>Вы можете голосовать за законопроекты</p>
        <? } ?>
        <? if ($user->post->org->can_create_bills) { ?>
            <p>Вы можете создавать новые законопроекты</p>
        <? } ?>
        <? if ($user->post->org->can_drop_stateleader) { ?>
            <p>Вы можете выдвинуть вотум недоверия лидеру государства</p>
        <? } ?>
        <? if ($user->post->org->leader_can_vote_for_bills && $user->isOrgLeader()) { ?>
            <p>Вы можете голосовать за законопроекты</p>
        <? } ?>
        <? if ($user->post->org->leader_can_create_bills && $user->isOrgLeader()) { ?>
            <p>Вы можете создавать новые законопроекты</p>
        <? } ?>
        <? if ($user->post->org->leader_can_veto_bills && $user->isOrgLeader()) { ?>
            <p>Вы можете накладывать вето на законопроекты</p>
        <? } ?>
        <? if ($user->post->canVoteForBills() || $user->post->canVetoBills()) { ?>
            <h3>Законопроекты на голосовании</h3>
            <?= BillListWidget::widget(['id' => 'bills_on_vote_list', 'showVoteButtons' => true, 'user' => $user, 'bills' => Bill::find()->where(['accepted' => 0, 'state_id' => $user->state_id])->all()]) ?>
        <? } ?>
        <script>
            function voteForBill(bill_id, variant) {
                json_request('vote-for-bill', {'bill_id': bill_id, 'variant': variant});
            }
        </script>
        <h3>Последние принятые законопроекты</h3>

        <p>Список последних законопроектов <input type="button" class="btn btn-xs btn-default" id="bills_show" value="Показать"></p>
        <?= BillListWidget::widget(['id' => 'bills_list', 'style' => 'display:none', 'showVoteButtons' => false, 'bills' => Bill::find()->where(['and', 'state_id = ' . $user->state_id, "accepted > 0"])->limit(10)->orderBy('id DESC')->all()]) ?>
        <script type="text/javascript">
            $('#bills_show').click(function () {
                if ($(this).val() === 'Показать') {
                    $(this).val('Скрыть');
                    $('#bills_list').slideDown();
                } else {
                    $(this).val('Показать');
                    $('#bills_list').slideUp();
                }
            })
        </script>

        <? if (count($user->post->stocks)) { ?>
            <h3>Управление предприятиями</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Фирма</th>
                        <th>Количество акций</th>
                        <th>Примерная рыночная стоимость</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($user->post->stocks as $stock) { ?>
                        <tr>
                            <td><a href="#" onclick="load_page('holding-info', {'id':<?= $stock->holding_id ?>})"><?= $stock->holding->name ?></a></td>
                            <td><?= MyHtmlHelper::formateNumberword($stock->count, "акций", "акция", "акции") ?> (<?= round($stock->getPercents(), 2) ?>%)</td>
                            <td>≈ <?= number_format($stock->getCost(), 0, '', ' ') ?> <?= MyHtmlHelper::icon('money') ?></td>
                            <td><?= Html::a("Управление", "#", ['class' => 'btn btn-green', 'onclick' => 'load_page("holding-control",{"id":' . $stock->holding_id . '})']) ?></td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        <? } ?>

        <h2>Действия</h2>

        <div class="btn-toolbar">
            <? if ($user->post->org->leader_post === $user->post_id) { ?>

                <button class="btn dropdown-toggle btn-blue" data-toggle="dropdown">
                    Управление организацией <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <? if ($user->post->org->leader_can_create_posts) { ?><li><a href="#" onclick="create_new_post(<?= $user->post->org_id ?>)" >Создать новую должность</a></li><? } ?>
                </ul>
                <script type="text/javascript">

                    function create_new_post(id) {
                        name = prompt('Введите название новой должности');
                        if (name != "null" && name) {
                            var name_ministry = prompt("Введите название организации, подчинённой этому посту");
                            if (name_ministry != "null" && name_ministry) {
                                json_request('create-post', {'id': id, 'name': name, 'name_ministry': name_ministry});
                            }
                        }
                    }

                    function naznach(id) {
                        load_modal('naznach', {'id': id}, 'naznach', 'naznach_body');
                    }

                    function set_post(uid, id, name, post_name) {
                        if (confirm('Вы действительно хотите назначить человека по имени ' + name + ' на должность «' + post_name + '»?')) {
                            json_request('set-post', {'id': id, 'uid': uid});
                            $('.modal-backdrop').remove();
                        }
                    }
                </script>
            <? } ?>

            <?
            if ($user->isStateLeader() && $user->state->leader_can_drop_legislature && $user->state->legislatureOrg) {
                ?>
                <button class="btn btn-red" onclick="if (confirm('Вы действительно хотите распустить организацию «<?= $user->state->legislatureOrg->name ?>»?')) {
                                json_request('drop-legislature');
                            }" >
                    Распустить парламент
                </button>
            <? } ?>
            <?
            if ($user->post->canCreateBills()) {
                $isDicktator = !!($user->isOrgLeader() && $user->post->org->leader_can_make_dicktator_bills);
                ?>

                <button class="btn btn-green" onclick="new_zakon_modal()" >
                    Новый закон
                </button>
                <div style="display:none;" class="modal fade" id="new_zakon_select_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel123">Новый законопроект</h3>
                            </div>
                            <div id="new_zakon_select_modal_body" class="modal-body">
                                <select id="new_zakon_select">
                                    <?
                                    $where = ['only_auto' => 0];
                                    if ($isDicktator) {
                                        
                                    } else {
                                        $where['only_dictator'] = 0;
                                    }
                                    foreach (BillProto::find()->where($where)->asArray()->all() as $bill_type) {
                                        $className = "app\\models\\bills\\proto\\" . $bill_type['class_name'];
                                        if ($className::isVisible($user->state)) {
                                            ?>
                                            <option value="<?= $className::$id ?>" ><?= htmlspecialchars($className::$name) ?></option>
                                        <? }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-green" onclick="new_zakon_form_modal()">Выбрать</button>
                                <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="display:none;" class="modal fade" id="new_zakon_form_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1234" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel1234">Новый законопроект</h3>
                            </div>
                            <div id="new_zakon_form_modal_body" class="modal-body">
                                Загрузка...
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-green" id="send_new_zakon">Отправить</button>
                                <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    function new_zakon_modal() {
                        $('#new_zakon_select_modal').modal();
                    }
                    var bill_proto_id;

                    function new_zakon_form_modal() {
                        bill_proto_id = $('#new_zakon_select').val();
                        load_modal('new-bill', {'id': bill_proto_id}, 'new_zakon_form_modal', 'new_zakon_form_modal_body');
                    }
                    $(function () {
                        $('#send_new_zakon').click(function () {
                            var fields = $('.bill_field');
                            var f = {'bill_proto_id': bill_proto_id};
                            for (var i = 0, l = fields.length; i < l; i++) {
                                var $f = $(fields[i]);
                                f[$f.attr("name")] = $f.val();
                                // console.log($f.val(),$f.attr("type"));
                                if ($f.attr("type") === "checkbox") {
                                    f[$f.attr("name")] = $f.prop("checked") ? 1 : 0;
                                }
                            }
                            json_request('new-bill', f);
                            $('#new_zakon_form_modal').modal('close');
                            return false;

                        })
                    })
                </script>
<? } ?>

            <button class="btn btn-red" onclick="self_drop_from_post()" >
                Уволиться
            </button>
            <script type="text/javascript">
                function self_drop_from_post() {
                    json_request('self-drop-from-post');
                }
            </script>
        </div>
    </div>
</div>
