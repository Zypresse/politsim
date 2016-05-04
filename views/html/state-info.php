<?php
/* @var $state app\models\State */

use app\components\MyHtmlHelper,
    yii\helpers\Html;

$KN = [
    'nation_individual_vote' => 'голосование населения за кандидатов',
    'nation_party_vote' => 'голосование населения за партии',
    'other_org_vote' => 'голосование членов другой организации',
    'org_vote' => 'голосование членов этой же организации',
    'unlimited' => 'пожизненно',
    'dest_by_leader' => 'назначаются лидером',
    'nation_one_party_vote' => 'голосование населения за членов единственной партии'
];

$show_create_party = isset($_GET['show_create_party']);
?>
<section class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="box" style="margin-top: 10px">
                <div class="box-content">
                    <img src="<?= $state->flag ?>" alt="Флаг" class="img-polaroid" style="width:100%">
                </div>
                <div class="box-footer">
                    <em>Национальный флаг</em>
                </div>
            </div>
            <?php if ($state->anthem && MyHtmlHelper::isSoundCloudLink($state->anthem)): ?>
                <div class="box" style="margin-top: 10px">
                    <div class="box-content">
                        <iframe id="sc-widget" src="https://w.soundcloud.com/player/?url=<?= $state->anthem ?>" width="100%" height="100" scrolling="no" frameborder="no"></iframe>
                    </div>
                    <div class="box-footer">
                        <em>Национальный гимн</em>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <div class="col-md-8">
            <h1><?= htmlspecialchars($state->name) ?> <small>(<?= htmlspecialchars($state->short_name) ?>)</small></h1>
            <p>
                <strong>Форма гос. устройства:</strong> <?= htmlspecialchars($state->structure->name) ?><br>
                <strong>Столица:</strong> <?= $state->capitalRegion->getCityHtmlName() ?><br>
                <strong>Население:</strong> <?= MyHtmlHelper::formateNumberword($state->population, 'h') ?>
                <?php if ($state->core): ?>
                    <br><strong>Считает себя наследником государства:</strong> <?= Html::img('/img/cores/' . $state->core->id . '.png'); ?> <?= $state->core->name ?> (контроллирует <?= number_format($state->getCoreCountryState()->percents * 100, 0) ?>% территорий)
                <?php endif ?>
            </p>
        </div>
    </div>
    <div class="col-md-12">
        <h3>Правительство</h3>
        <p><strong><?php if ($state->executiveOrg && $state->executiveOrg->leader): ?><?= htmlspecialchars($state->executiveOrg->leader->name) ?><?php else: ?>Лидер государства<?php endif ?></strong>:
            <?php if ($state->executiveOrg && $state->executiveOrg->leader && $state->executiveOrg->leader->user): ?>
                <?= $state->executiveOrg->leader->user->getHtmlName() ?>
                (<?php if ($state->executiveOrg && $state->executiveOrg->leader->user->party): ?><a href="#" onclick="load_page('party-info', {'id':<?= $state->executiveOrg->leader->user->party_id ?>})"><?= htmlspecialchars($state->executiveOrg->leader->user->party->name) ?></a><?php else: ?><?php if ($state->executiveOrg->leader->user->sex === 1): ?>Беспартийная<?php else: ?>Беспартийный<?php endif; endif ?>)

                <span class="star"><?= $state->executiveOrg->leader->user->star ?> <?= MyHtmlHelper::icon('star') ?></span>
                <span class="heart"><?= $state->executiveOrg->leader->user->heart ?> <?= MyHtmlHelper::icon('heart') ?></span>
                <span class="chart_pie"><?= $state->executiveOrg->leader->user->chart_pie ?> <?= MyHtmlHelper::icon('chart_pie') ?></span>
            <?php else: ?>
                не назначен
            <?php endif ?>
        </p>
        <h4><?php if ($state->executiveOrg): ?><a href="#" onclick="load_page('org-info', {'id':<?= $state->executive ?>});"><?= htmlspecialchars($state->executiveOrg->name) ?></a><?php else: ?>Не сформирована<?php endif ?> <small>Исполнительная власть</small></h4>
        <?php if ($state->executiveOrg && $state->executiveOrg->isElected()): ?>Следующие выборы — <span class="formatDate" data-unixtime="<?= $state->executiveOrg->next_elect ?>"><?= date("d-M-Y H:i", $state->executiveOrg->next_elect) ?></span><br><?php endif ?>
        <?php if ($state->executiveOrg && $state->executiveOrg->isLeaderElected()): ?>Следующие выборы лидера организации — <span class="formatDate" data-unixtime="<?= $state->executiveOrg->next_elect ?>"><?= date("d-M-Y H:i", $state->executiveOrg->next_elect) ?></span><br><?php endif ?>
        <h4><?php if ($state->legislatureOrg): ?><a href="#" onclick="load_page('org-info', {'id':<?= $state->legislature ?>});"><?= htmlspecialchars($state->legislatureOrg->name) ?></a><?php else: ?>Не сформирована<?php endif ?> <small>Законодательная власть</small></h4>
        <?php if ($state->legislatureOrg && $state->legislatureOrg->isElected()): ?>Следующие выборы — <span class="formatDate" data-unixtime="<?= $state->legislatureOrg->next_elect ?>"><?= date("d-M-Y H:i", $state->legislatureOrg->next_elect) ?></span><br><?php endif ?>
        <?php if ($state->legislatureOrg && $state->legislatureOrg->isLeaderElected()): ?>Следующие выборы лидера организации — <span class="formatDate" data-unixtime="<?= $state->legislatureOrg->next_elect ?>"><?= date("d-M-Y H:i", $state->legislatureOrg->next_elect) ?></span><br><?php endif ?>

        <h3>Конституция</h3>
        <ul>
            <?php foreach ($state->articles as $article): ?>

                <li>
                    <strong><?= $article->proto->name ?></strong> — 
                    <?php if ($article->proto->type === 'checkbox'): ?>
                        <?= ($article->value) ? 'ДА' : 'НЕТ' ?>
                    <?php elseif ($article->proto->type === 'org_dest_members' || $article->proto->type === 'org_dest_leader'): ?>
                        <?= $KN[$article->value] ?>
                    <?php else: ?>
                        <?= $article->value ?>
                    <?php endif ?>
                </li>

            <?php endforeach ?>
        </ul>

        <h3>Экономика</h3>
        <ul>
            <?php foreach ($state->licenses as $license): ?>
            <?php if (!$license->proto) $license->delete () ?>
                <li><strong><?= $license->proto->name ?>:</strong><br>
                    <?php if ($license->is_only_goverment): ?>
                        Гос. монополия
                    <?php else: ?>
                        Стоимость лицензии: <?= MyHtmlHelper::moneyFormat($license->cost) ?><br>
                        Стоимость лицензии для нерезидентов: <?= MyHtmlHelper::moneyFormat($license->cost_noncitizens) ?>
                        <?php if ($license->is_need_confirm): ?>
                            <br> Требуется подтверждение министра
                        <?php endif ?>
                        <?php if ($license->is_need_confirm_noncitizens): ?>
                            <br> Требуется подтверждение министра для нерезидентов
                        <?php endif ?>
                    <?php endif ?>
                </li>
            <?php endforeach ?>
        </ul>

        <h3>Территория</h3>
        <strong>Список регионов:</strong> <input type="button" class="btn btn-sm btn-default" id="regions_show" value="Показать">
        <ul id="region_list" >
            <?php foreach ($state->regions as $region): ?>
                <li><a href="#" onclick="show_region('<?= $region->code ?>')"><?= htmlspecialchars($region->name) ?> (<?= MyHtmlHelper::formateNumberword($region->population, 'h') ?>)</a></li>
            <?php endforeach ?>
        </ul>

        <h3>Действия</h3>

        <div class="btn-toolbar">
            <div class="btn-group">
                <button class="btn dropdown-toggle btn-info" data-toggle="dropdown">
                    Гражданство <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <!--<li class="divider"></li>-->
                    <?php if ($user->state_id === $state->id): ?>
                        <li><a href="#" onclick="json_request('drop-citizenship')" >Отказаться от гражданства</a></li>
                    <?php else: ?>
                        <li>
                            <a href="#" onclick="<?php if ($user->state_id === 0): ?> json_request('get-citizenship', {'state_id':<?= $state->id ?>}) <?php else: ?> show_custom_error('Вы уже имеете гражданство другого государства. Во время альфа теста иметь двойное гражданство запрещено.') <?php endif ?>" >
                                Получить гражданство
                            </a>
                        </li>
                    <?php endif ?>
                    <!--<li><a href='#' >Запросить политическое убежище</a></li>-->
                </ul>
            </div>

            <div class="btn-group">
                <button class="btn dropdown-toggle btn-primary" data-toggle="dropdown">
                    Политика <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <!--<li class="divider"></li>-->
                    <?php if ($user->state_id === $state->id): ?>
                        <?php if ($user->party_id === 0 && $state->allow_register_parties): ?><li><a href='#' onclick="$('#create_party').modal();" >Создать партию</a></li><?php endif ?>
                    <?php endif ?>
                    <li><a href='#' onclick="load_page('chart-parties', {'state_id':<?= $state->id ?>});" >Список партий</a></li>
                    <li><a href='#' onclick="load_page('elections', {'state_id':<?= $state->id ?>});" >Выборы</a></li>
                </ul>
            </div>
            
            <?php if (!$state->executiveOrg || !$state->executiveOrg->leader || !$state->executiveOrg->leader->user): ?>
            
            <div class="btn-group">
                <button class="btn btn-danger" onclick="json_request('seize-power', {'state_id':<?= $state->id ?>})" >
                    Захватить власть
                </button>
            </div>
            <?php endif ?>
        </div><?php if ($state->allow_register_parties || $show_create_party): ?>
            <div style="display:none" class="modal" id="create_party" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <?php if (!$show_create_party): ?><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><?php endif ?>
                    <h3 id="myModalLabel">Создание партии</h3>
                </div>

                <div id="create_party_body" class="modal-body">
                    <form class="well form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="#party_name">Название</label>
                            <div class="controls">
                                <input type="text" id="party_name" placeholder="Единая Россия">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="#party_name_short">Короткое название</label>
                            <div class="controls">
                                <input type="text" id="party_name_short" placeholder="ЕР">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="#party_ideology">Идеология</label>
                            <div class="controls">
                                <select id="party_ideology" >
                                    <?php foreach ($ideologies as $ideology): ?>
                                        <option value="<?= $ideology->id ?>"><?= htmlspecialchars($ideology->name) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="#party_image">Ссылка на логотип<br><small>Используйте сервисы загрузки изображений, например <a href="https://imgur.com" target="_new">Imgur</a></small></label>
                            <div class="controls">
                                <input type="text" id="party_image" placeholder="https://i.imgur.com/TNBKSPO.gif">
                            </div>
                        </div>
                        <!--<span class="help-block">Example block-level help text here.</span>
                        <label class="checkbox">
                          <input type="checkbox"> Check me out
                        </label>
                        <button type="submit" class="btn btn-success">Submit</button>-->
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="create_party()">Создать</button>
                    <?php if (!$show_create_party): ?><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button><?php endif ?>
                </div>
            </div>
            <script type="text/javascript">
                function create_party() {
                    name = $('#party_name').val();
                    name_short = $('#party_name_short').val();
                    image = $('#party_image').val();
                    ideology = $('#party_ideology').val();
                    //$('.modal-backdrop').hide(); 
                    json_request('create-party', {'name':name, 'short_name':name_short, 'image':image, 'ideology':ideology, 'firsth_of_state':firsth_of_state}, false);
                    load_page('party-info', {}, 500);
                    return true;
                }
            </script>
            <?php endif ?>
        <script>
            var firsth_of_state = false;
            $(function(){
            <?php if ($show_create_party): ?>
                firsth_of_state = true;
                $('#create_party').modal({'keyboard':false, 'backdrop':'static'});
            <?php endif ?>
                $('#regions_show').click(function() {
                    if ($(this).val() === 'Показать') {
                        $(this).val('Скрыть');
                        $('#region_list').slideDown();
                    } else {
                        $(this).val('Показать');
                        $('#region_list').slideUp();
                    }
                })
            })

        </script>
    </div>
</section>
