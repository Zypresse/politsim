<?php

use app\components\MyHtmlHelper,
    app\components\widgets\BillListWidget,
    app\models\bills\Bill,
    yii\helpers\Html;
?>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <h1><?= htmlspecialchars($org->name) ?></h1>
            <p><?php if ($org->isLegislature()) { ?>Законодательная власть<?php } elseif ($org->isExecutive()) { ?>Исполнительная власть<?php } else { ?>Организация<?php } ?> государства <?=$org->state->getHtmlName()?></p>
            <p><?php                 switch ($org->dest) {
                    case 'dest_by_leader':
                        ?>
                        Члены организации назначаются лидером этой организации.
                        <?php break;
                    case 'nation_party_vote':
                        ?>
                        Члены организации избираются народным голосованием по партийным спискам.
                        <?php break;
                    case 'nation_one_party_vote':
                        ?>
                        Члены организации избираются народным голосованием из кандидатов от правящей партии.
                        <?php break;
                    default:
                        ?>
                        Способ формирования организации неизвестен.
                        <?php break;
                }
                ?></p>
                <?php if ($org->isElected()) { ?><p>Члены организации переизбираются раз в <?= MyHtmlHelper::formateNumberword($org->elect_period, 'дней', 'день', 'дня') ?></p><?php } ?>
            <p><?php                 switch ($org->leader_dest) {
                    case 'org_vote':
                        ?>
                        Лидер организации избирается голосованием членов этой организации.
                        <?php break;
                    case 'nation_party_vote':
                        ?>
                        Лидер организации избирается народным голосованием по партийным спискам.
                        <?php break;
                    case 'nation_individual_vote':
                        ?>
                        Лидер организации избирается народным голосованием.
                        <?php break;
                    case 'other_org_vote':
                        ?>
                        Лидер организации избирается голосованием членов другой организации.
                    <?php break;
                case 'unlimited':
                    ?>
                        Лидер организации назначается предшественником.
                    <?php break;
                default:
                    ?>
                        Способ назначения лидера организации неизвестен.
        <?php break;
}
?></p>
            <?php if ($org->can_vote_for_bills) { ?>
                <p>Члены организации могут предлагать законопроекты</p>
            <?php } ?>
            <?php if ($org->can_create_bills) { ?>
                <p>Члены организации могут создавать законопроекты</p>
            <?php } ?>
            <?php if ($org->isLeaderElected()) { ?><p>Лидер организации переизбирается раз в <?= MyHtmlHelper::formateNumberword($org->elect_period, 'дней', 'день', 'дня') ?></p><?php } ?>
            <h3><?php if ($org->leader && $org->leader->name) { ?><?= htmlspecialchars($org->leader->name) ?><?php } else { ?>Лидер организации<?php } ?></h3>
                <?php if ($org->leader && $org->leader->user) { ?><p><a href="#" onclick="load_page('profile', {'uid':<?= $org->leader->user->id ?>})"><img src="<?= $org->leader->user->photo ?>" alt="" style="width:32px;height:32px;"></a>
                    <a href="#" onclick="load_page('profile', {'uid':<?= $org->leader->user->id ?>})"><?= htmlspecialchars($org->leader->user->name) ?></a>
                    (<?php if ($org->leader->user->party_id) { ?><a href="#" onclick="load_page('party-info', {'id':<?= $org->leader->user->party_id ?>});"><?= htmlspecialchars($org->leader->user->party->name) ?></a><?php } else {
                    if ($org->leader->user->sex === 1) { ?>Беспартийная<?php } else { ?>Беспартийный<?php } ?><?php } ?>)
                    <span class="star"><?= $org->leader->user->star ?> <?= MyHtmlHelper::icon('star') ?></span>
                    <span class="heart"><?= $org->leader->user->heart ?> <?= MyHtmlHelper::icon('heart') ?></span>
                    <span class="chart_pie"><?= $org->leader->user->chart_pie ?> <?= MyHtmlHelper::icon('chart_pie') ?></span>
                </p>
            <?php } else { ?><p>Лидер организации не назначен</p>
                <?php if ($org->leader_dest === $org::DEST_ORG_VOTE) { ?>
                    <h5>Заявки на пост:</h5><?php                     if (count($org->speakerRequests)) {
                        ?><dl><?php                         foreach ($org->speakerRequests as $request) {
                            ?>
                                <dt><?= $request->candidat->name ?> (<?= Html::a($request->party->name, '#', ['onclick' => 'load_page("party_info",{"id":' . $request->party_id . '})']) ?>)</dt>     
                                <dd>Поддержало <strong><?= $request->getVotesCount() ?> голосов</strong></dd>
                <?php             }
            ?></dl><?php         } else {
            echo "<p>Ни одна партия ещё не подала заявок</p>";
        }
        ?>
                    <p>Выборы лидера организации продлятся до <span class="formatDate" data-unixtime="<?= $org->next_elect - $org->elect_period * 24 * 60 * 60 + 24 * 60 * 60 ?>"><?= date('d-M-Y H:i', $org->next_elect - $org->elect_period * 24 * 60 * 60 + 24 * 60 * 60) ?></span></p>
    <?php } ?><?php } ?>

            <?php if ($org->can_vote_for_bills || $org->can_create_bills || $org->leader_can_vote_for_bills || $org->leader_can_create_bills) { ?>
                <h3>Законопроекты на голосовании</h3>
                <p>Список последних законопроектов <button class="btn btn-xs btn-default" id="bills_show">Показать</button></p>
                <?= BillListWidget::widget(['id' => 'bills_list', 'style' => 'display:none', 'showVoteButtons' => false, 'bills' => Bill::find()->where(['accepted' => 0, 'state_id' => $org->state_id])->all()]) ?>
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
                    <?php } ?>

            <h3>Члены организации</h3>
            <p>В организации <?= $org->getUsersCount() ?> из <?= $org->getPostsCount() ?> участников<br>

            <div class="row">
                <div class="col-md-10 col-md-offset-1" style="text-align:center">

<?php foreach ($org->posts as $player) { ?>
    <?php if ($player->user) { ?>
                            <a href="#" onclick="$('.org_member').popover('destroy');
                        load_page('profile', {'uid':<?= $player->user->id ?>})" rel="popover" class="org_member" data-content="<img src='<?= $player->user->photo ?>' class='img-polaroid popover_avatar' alt='' ><p><strong><?= htmlspecialchars($player->user->name) ?></strong> <?php if ($player->user->party_id) { ?>(<?= htmlspecialchars($player->user->party->short_name) ?>)<?php } ?></p><p style='margin-top:10px'><?= $player->user->star ?><img src='/img/star.png' alt='' > <?= $player->user->heart ?><img src='/img/heart.png' alt=''> <?= $player->user->chart_pie ?><img src='/img/chart_pie.png' alt='' ><?= $player->partyReserve ? "<br><span style='font-size:70%'>Пост зарезервирован партией «{$player->partyReserve->short_name}»</span>" : "" ?></p>" data-original-title="<?= htmlspecialchars($player->name) ?>" >
                                <img style="background-color:<?= $player->partyReserve ? MyHtmlHelper::getPartyColor($player->partyReserve->ideologyInfo->d, true) : '#eee' ?>" src="<?= $player->user->photo ?>" class="img-polaroid" alt="<?= htmlspecialchars($player->user->name) ?>">
                            </a>
    <?php } else { ?>
                            <a href="#" rel="popover" class="org_member" data-content="<p>Не назначен<?= $player->partyReserve ? "<br><span style='font-size:70%'>Пост зарезервирован партией «{$player->partyReserve->short_name}»</span>" : "" ?></p>" data-original-title="<?= htmlspecialchars($player->name) ?>" >
                                <img style="background-color:<?= $player->partyReserve ? MyHtmlHelper::getPartyColor($player->partyReserve->ideologyInfo->d, true) : '#eee' ?>" src="/img/chair.png" class="img-polaroid" alt="<?= htmlspecialchars($player->name) ?>">
                            </a>
    <?php } ?>
<?php } ?>
                </div></div>
            <script type="text/javascript">
                $(function () {
                    $('.org_member').popover({'placement': 'top'});
                })
            </script>
        </div>
    </div>
</section>