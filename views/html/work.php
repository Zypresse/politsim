<?php

/** @var app\models\User $user */

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
        <div class="col-md-12">
            <h1>Личный кабинет</h1>
            <p>Вы занимаете должность &laquo;<?= htmlspecialchars($user->post->name) ?>&raquo; в организации &laquo;<?= $user->post->org->getHtmlName() ?>&raquo;
                <?php if ($user->post->balance): ?>
                <p>
                    На данной должности вам доступен бюджет в размере <?= MyHtmlHelper::moneyFormat($user->post->balance) ?>
                </p>
            <?php endif ?>
            <?php if ($user->post_id === $user->post->org->leader_post): ?>
                <p>
                    Вы — лидер организации &laquo;<?= $user->post->org->getHtmlName() ?>&raquo;
                    <?php if ($user->post->org->leader_can_create_posts): ?> и можете создавать новые должности в ней<?php endif ?>.
                </p>
                <h3>Подчинённые</h3>
                <p>
                    <strong>Список членов организации:</strong> <input type="button" class="btn btn-xs btn-default" id="posts_show" value="Показать"></p>
                <table id="posts_list" class="table" >
                    <?php foreach ($user->post->org->posts as $player): ?>
                        <tr>
                            <td>
                                <strong><?= $player->name ?></strong>
                                <?php if ($player->can_delete): ?>
                                <button class="btn btn-red" onclick="delete_post(<?= $player->id ?>)" style="float:right;">Удалить</button>
                                <?php endif ?>
                            </td>
                            <td>
                                <?php if ($player->user): ?>
                                <?=$player->user->getHtmlName()?>
                                (<?php if ($player->user->party): ?><?=$player->user->party->getHtmlName()?><?php else: ?><?php if ($player->user->sex === 1): ?>Беспартийная<?php else: ?>Беспартийный<?php endif ?><?php endif ?>)
                                <span class="star"><?= $player->user->star ?> <?= MyHtmlHelper::icon('star') ?></span>
                                <span class="heart"><?= $player->user->heart ?> <?= MyHtmlHelper::icon('heart') ?></span>
                                <span class="chart_pie"><?= $player->user->chart_pie ?> <?= MyHtmlHelper::icon('chart_pie') ?></span>

                                <?php if ($user->post->org->dest === 'dest_by_leader' && $player->id !== $user->post->org->leader_post): ?>
                                <button class="btn btn-red" onclick="drop_from_post(<?= $player->id ?>)">Сместить с поста</button>
                                <?php endif ?>
                                <?php else: ?>
                                <?php if ($user->post->org->dest === 'dest_by_leader'): ?>
                                <button class="btn btn-green" onclick="naznach(<?= $player->id ?>)">Назначить</button>
                                <?php else: ?>
                                Не назначен
                                <?php endif ?>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </table>
            <?php endif ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if ($user->post->org->leader_dest === Org::DEST_ORG_VOTE): ?>
            <h4>Заявки на пост <?= $user->post->org->leader->name ?>:</h4>
            <?php if (count($user->post->org->speakerRequests)): ?>
            <dl>
            <?php foreach ($user->post->org->speakerRequests as $request): ?>
                <dt><?= $request->candidat->getHtmlName() ?> (<?=$request->party->getHtmlName()?>)</dt>     
                <dd>Поддержало <strong><?= MyHtmlHelper::formateNumberword($request->getVotesCount(), 'голосов', 'голос', 'голоса') ?></strong> 
                    <?php if ($user->post->org->isAllreadySpeakerVoted($user->post_id)): ?>
                    
                    <?php else: ?>
                    <button class="btn btn-sm btn-default" onclick="json_request('vote-about-org-leader', {'request_id':<?= $request->id ?>})">Проголосовать</button>
                    <?php endif ?>
                </dd>
            <?php endforeach ?>
            </dl>
            <?php else: ?>
            <p>Ни одна партия ещё не подала заявок</p>
            <?php endif ?>
            <?php endif ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">    
            <?php if ($user->isStateLeader() && $user->state->executiveOrg->leader_can_make_dicktator_bills): ?>
            <p>Вы можете принимать законы единолично</p>
            <?php endif ?>
            <?php if ($user->isStateLeader() && $user->state->leader_can_drop_legislature && $user->state->legislatureOrg): ?>
            <p>Вы можете распустить организацию «<a href="#" onclick="load_page('org-info', {'id':<?= $user->state->legislature ?>});"><?= $user->state->legislatureOrg->name ?></a>»</p>
            <?php endif ?>
            <?php if ($user->post->org->can_vote_for_bills): ?>
            <p>Вы можете голосовать за законопроекты</p>
            <?php endif ?>
            <?php if ($user->post->org->can_create_bills): ?>
            <p>Вы можете создавать новые законопроекты</p>
            <?php endif ?>
            <?php if ($user->post->org->can_drop_stateleader): ?>
            <p>Вы можете выдвинуть вотум недоверия лидеру государства</p>
            <?php endif ?>
            <?php if ($user->post->org->leader_can_vote_for_bills && $user->isOrgLeader()): ?>
            <p>Вы можете голосовать за законопроекты</p>
            <?php endif ?>
            <?php if ($user->post->org->leader_can_create_bills && $user->isOrgLeader()): ?>
            <p>Вы можете создавать новые законопроекты</p>
            <?php endif ?>
            <?php if ($user->post->org->leader_can_veto_bills && $user->isOrgLeader()): ?>
            <p>Вы можете накладывать вето на законопроекты</p>
            <?php endif ?>
            <?php if ($user->post->canVoteForBills() || $user->post->canVetoBills()): ?>
            <h3>Законопроекты на голосовании</h3>
            <?= BillListWidget::widget(['id' => 'bills_on_vote_list', 'showVoteButtons' => true, 'user' => $user, 'bills' => Bill::find()->where(['accepted' => 0, 'state_id' => $user->state_id])->all()]) ?>
            <?php endif ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">    
            <h3>Последние принятые законопроекты</h3>
            <p>Список последних законопроектов <input type="button" class="btn btn-xs btn-default" id="bills_show" value="Показать"></p>
            <?= BillListWidget::widget(['id' => 'bills_list', 'style' => 'display:none', 'showVoteButtons' => false, 'bills' => Bill::find()->where(['and', 'state_id = ' . $user->state_id, "accepted > 0"])->limit(10)->orderBy('id DESC')->all()]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if (count($user->post->stocks)): ?>
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
                    <?php foreach ($user->post->stocks as $stock): ?>
                    <tr>
                        <td><?=$stock->holding->getHtmlName()?></td>
                        <td><?= MyHtmlHelper::formateNumberword($stock->count, "акций", "акция", "акции") ?> (<?= round($stock->getPercents(), 2) ?>%)</td>
                        <td>≈ <?= MyHtmlHelper::moneyFormat($stock->getCost()) ?></td>
                        <td><?= Html::a("Управление", "#", ['class' => 'btn btn-green', 'onclick' => 'load_page("holding-control",{"id":' . $stock->holding_id . '})']) ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?php endif ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2>Действия</h2>
            
            <div class="btn-toolbar">
                <?php if ($user->post->org->leader_post === $user->post_id): ?>

                <button class="btn dropdown-toggle btn-blue" data-toggle="dropdown">
                    Управление организацией <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <?php if ($user->post->org->leader_can_create_posts): ?><li><a href="#" onclick="create_new_post(<?= $user->post->org_id ?>)" >Создать новую должность</a></li><?php endif ?>
                </ul>
                <?php endif ?>

                <?php if ($user->isStateLeader() && $user->state->leader_can_drop_legislature && $user->state->legislatureOrg): ?>
                <button class="btn btn-red" onclick="if (confirm('Вы действительно хотите распустить организацию «<?= $user->state->legislatureOrg->name ?>»?')) { json_request('drop-legislature'); }" >
                    Распустить парламент
                </button>
                <?php endif ?>
                <?php if ($user->post->canCreateBills()):
                    $isDicktator = !!($user->isOrgLeader() && $user->post->org->leader_can_make_dicktator_bills);
                ?>
                <button class="btn btn-green" onclick="new_zakon_modal()" >
                    Новый закон
                </button>
                <?php endif ?>
                <button class="btn btn-red" onclick="self_drop_from_post()" >
                    Уволиться
                </button>
            </div>
        </div>
    </div>
</div>

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
<div style="display:none;" class="modal fade" id="new_zakon_select_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel123">Новый законопроект</h3>
            </div>
            <div id="new_zakon_select_modal_body" class="modal-body">
                <select id="new_zakon_select">
                    <?php
                    $where = ['only_auto' => 0];
                    if ($isDicktator) {

                    } else {
                        $where['only_dictator'] = 0;
                    }
                    foreach (BillProto::find()->where($where)->asArray()->all() as $bill_type):
                    $className = "app\\models\\bills\\proto\\" . $bill_type['class_name'];
                    if ($className::isVisible($user->state)):
                    ?>
                    <option value="<?= $className::$id ?>" ><?= htmlspecialchars($className::$name) ?></option>
                    <?php
                        endif;
                        endforeach;
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
<script type="text/javascript">

    $(function(){
        $('#posts_show').click(function () {
            if ($(this).val() === 'Показать') {
                $(this).val('Скрыть');
                $('#posts_list').slideDown();
            } else {
                $(this).val('Показать');
                $('#posts_list').slideUp();
            }
        });

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
        });
        
        $('#bills_show').click(function () {
            if ($(this).val() === 'Показать') {
                $(this).val('Скрыть');
                $('#bills_list').slideDown();
            } else {
                $(this).val('Показать');
                $('#bills_list').slideUp();
            }
        })
    });

    function delete_post(id) {
        if (confirm('Вы действительно хотите удалить эту должность?')) {
            json_request('delete-post', {'id': id});
        }
    }

    function drop_from_post(id) {
        if (confirm('Вы действительно хотите сместить этого человека с поста?')) {
            json_request('drop-from-post', {'id': id});
        }
    }
    
    function voteForBill(bill_id, variant) {
        json_request('vote-for-bill', {'bill_id': bill_id, 'variant': variant});
    } 
    
    function new_zakon_modal() {
        $('#new_zakon_select_modal').modal();
    }
    
    var bill_proto_id;

    function new_zakon_form_modal() {
        bill_proto_id = $('#new_zakon_select').val();
        load_modal('new-bill', {'id': bill_proto_id}, 'new_zakon_form_modal', 'new_zakon_form_modal_body');
    }
    
    function self_drop_from_post() {
        json_request('self-drop-from-post');
    }
    
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