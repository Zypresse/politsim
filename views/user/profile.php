<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator,
    yii\helpers\ArrayHelper;

/* @var $this \yii\web\View */
/* @var $user \app\models\User */
/* @var $isOwner boolean */

$viewer = Yii::$app->user->identity;

?>

<section class="content">
    <div class="row">
        <div class="col-md-4">
            <div class=" box" >
                <div class="box-body">
                    <?=Html::img($user->avatarBig, ['class' => 'img-polaroid', 'style' => 'width: 100%'])?>
                    <div class="photo_bottom_container">
                        <span class="star" ><?= $user->fame ?> <?= MyHtmlHelper::icon('star') ?></span>
                        <span class="heart" ><?= $user->trust?> <?= MyHtmlHelper::icon('heart') ?></span>
                        <span class="chart_pie" ><?= $user->success ?> <?= MyHtmlHelper::icon('chart_pie') ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                    <h1><?= Html::encode($user->name) ?> <?php if ($isOwner): ?><small>(это вы)</small><?php endif ?></h1>
                </div>
                <div class="box-body">
                    <?php if ($user->ideology):  ?>
                    <p>
                        <i class="fa fa-flag"></i>
                        <?php if ($user->ideology) : ?>
                            Придерживается идеологии «<?= $user->ideology->name ?>»
                        <?php endif ?>
                    </p>
                    <?php endif ?>
                    <?php if ($user->religion):  ?>
                    <p>
                        <i class="fa">☪</i>
                        <?php if ($user->religion) : ?>
                            Придерживается идеологии «<?= $user->religion->name ?>»
                        <?php endif ?>
                    </p>
                    <?php endif ?>
                    <p>
                        <i class="fa fa-group"></i>
                        <?php if (count($user->parties)): ?>
                        <?php
                            $partyLinks = [];
                            foreach ($user->parties as $party) {
                                $partyLinks[] = LinkCreator::partyLink($party);
                            }
                        ?>
                        Состоит в партиях: <?=implode(', ', $partyLinks)?>
                        <?php else: ?>
                            <?=$user->genderId === 1 ? 'Беспартийная' : 'Беспартийный' ?>
                        <?php endif ?>
                    </p>
                    <p>
                        <i class="fa fa-globe"></i>
                        <?php if (count($user->states)): ?>
                        <?php
                            $stateLinks = [];
                            foreach ($user->states as $state) {
                                $stateLinks[] = LinkCreator::stateLink($state);
                            }
                        ?>
                            Имеет гражданства: <?=implode(', ', $stateLinks)?>
                        <?php else: ?>
                            Не имеет гражданства
                        <?php endif ?>
                    </p>            
                    <p>                
                        <i class="fa fa-briefcase"></i>
                        <?php if (count($user->posts)): ?>
                            Занимает посты в правительстве
                        <?php else: ?>
                            Не занимает постов в правительстве
                        <?php endif ?>
                    </p>
                </div>
                <div class="box-footer">
                <?php if ($isOwner): ?>
                    <div class="btn-toolbar">
                        <div class="btn-group">
                            <button id="choose-ideology-btn" class="btn btn-sm btn-primary"><i class="fa fa-flag"></i> &nbsp; <?=Yii::t('app', 'Change ideology')?></button>
                            <button id="choose-religion-btn" class="btn btn-sm btn-primary"><i class="fa">☪</i> &nbsp; <?=Yii::t('app', 'Change religion')?></button>
                        </div>
                    </div>
                <?php endif ?>
            
            <?php if (!$isOwner) { ?>
                <div class="btn-toolbar">
                    <div class="btn-group">
                        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            Провести сделку <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href='#' onclick="transfer_money()" >Передать деньги</a></li>
                            <li><a href='#' onclick="transfer_stocks()" >Передать акции</a></li>
                            <li class="divider"></li>
                            <li><a href="#" onclick="sell_stocks()" >Продать акции</a></li>
                        </ul>
                    </div><div class="btn-group">
                        <button class="btn btn-gray dropdown-toggle" data-toggle="dropdown">
                            Сделать публичное заявление <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href='#' onclick="public_statement('positive')" >Поддержать этого политика</a></li>
                            <li><a href='#' onclick="public_statement('negative')" >Негативно высказаться</a></li>
                            <li><a href="#" onclick="public_statement('affront')" >Публично оскорбить</a></li>
                        </ul>
                    </div><div class="btn-group">
                        <button class="btn btn-gold dropdown-toggle" data-toggle="dropdown">
                            Публикации <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href='#' onclick="load_page('twitter/profile', {'id':<?= $user->id ?>})" >Микроблог</a></li>
                        </ul>
                    </div>
                </div>
                <div style="display:none" class="modal fade" id="transfer_money_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel">Передать деньги</h3>
                            </div>
                            <div id="transfer_money_dialog_body" class="modal-body">
                                <form class="well form-horizontal">
                                    <div class="control-group">
                                        <label class="control-label" for="#money_transfer_count">Количество</label>
                                        <div class="controls">
                                            <input type="number" id="money_transfer_count" placeholder="100"> <img src="/img/coins.png" alt="золотых монет" title="золотых монет" style="">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" >Способ</label>
                                        <div class="controls">   
                                            <label><input type="checkbox" value="hidden" name="money_transfer_hidden" id="money_transfer_hidden"> Тайно</label>
                                            <label><input type="checkbox" value="anonym" name="money_transfer_anonym" id="money_transfer_anonym"> Анонимно</label>
                                            <span id="money_transfer_type_open" class="help-block money_transfer_help-block">О передаче денег узнает любой, кто захочет узнать</span>

                                            <span id="money_transfer_type_hidden" class="help-block money_transfer_help-block">О передаче денег узнают разве что спецслужбы</span>
                                            <span id="money_transfer_type_anonym" class="help-block money_transfer_help-block">Получатель не узнает, кто передал деньги</span>
                                            <input type="hidden" id="money_transfer_type" value="open">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button onclick="send_money()" class="btn btn-success" data-dismiss="modal" aria-hidden="true">Передать</button>
                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="display:none" class="modal fade" id="transfer_stocks_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel">Заключение сделки</h3>
                            </div>
                            <div id="transfer_stocks_dialog_body" class="modal-body">
                                <form class="well form-horizontal">
                                    <div class="control-group">
                                        <label class="control-label" for="#holding_id">Компания</label>
                                        <div class="controls">
                                            <select id="holding_id" >
                                                <?php foreach ($viewer->stocks as $stock): if ($stock->holding): ?>
                                                        <option value="<?= $stock->holding_id ?>"><?= $stock->holding->name ?> (<?= number_format($stock->count, 0, '', ' ') ?>)</option>
                                                    <?php endif;
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group" id="dealing_cost_block">
                                        <label class="control-label" for="#dealing_cost">Цена</label>
                                        <div class="controls">
                                            <input type="number" id="dealing_cost" placeholder="" > <?= MyHtmlHelper::icon('money') ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="#dealing_stocks_count">Количество</label>
                                        <div class="controls">
                                            <input type="number" id="dealing_stocks_count" placeholder="">
                                        </div>
                                    </div>
                                    <div class="control-group" id="dealing_stocks_pricebyone_block">
                                        <label class="control-label" >Цена за акцию</label>
                                        <div class="controls">
                                            <input type="number" id="dealing_stocks_pricebyone" readonly="readonly" placeholder=""> <?= MyHtmlHelper::icon('money') ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button onclick="create_stocks_dealing()" class="btn btn-success" data-dismiss="modal" aria-hidden="true">Создать</button>
                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
                            </div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">

                    function update_stocks_count() {
                        if ($('#dealing_stocks_count').val()) {
                            $('#dealing_stocks_pricebyone').val($('#dealing_cost').val() / $('#dealing_stocks_count').val());
                        } else {
                            $('#dealing_stocks_pricebyone').val(0);
                        }
                    }

                    function transfer_money() {
                        $('.money_transfer_help-block').hide();
                        $('#money_transfer_type_open').show();
                        $('#money_transfer_hidden').change(function () {
                            if ($(this).prop('checked')) {
                                $('#money_transfer_type_hidden').show();
                                $('#money_transfer_type_open').hide();
                            } else {
                                $('#money_transfer_type_hidden').hide();
                                $('#money_transfer_type_open').show();
                            }
                        });
                        $('#money_transfer_anonym').change(function () {
                            if ($(this).prop('checked')) {
                                $('#money_transfer_type_anonym').show();
                            } else {
                                $('#money_transfer_type_anonym').hide();
                                if (!$('#money_transfer_hidden').prop('checked'))
                                    $('#money_transfer_type_open').show();
                            }
                        });
                        $('#money_transfer_count').change(function () {
                            if ($(this).val() < 0) {
                                $(this).val(0);
                            } else if ($(this).val() ><?= $user->money ?>) {
                                $(this).val(<?= $user->money ?>);
                            }


                        });
                        $('#transfer_money_dialog').modal();
                    }

                    function transfer_stocks() {
                        $('#dealing_cost').val(0);
                        $('#dealing_cost_block').hide();
                        $('#dealing_stocks_pricebyone_block').hide();
                        $('#transfer_stocks_dialog').modal();
                    }


                    function sell_stocks() {
                        $('#dealing_cost').val('');
                        $('#dealing_cost_block').show();
                        $('#dealing_stocks_pricebyone_block').show();

                        $('#dealing_cost').keyup(update_stocks_count);
                        $('#dealing_cost').change(update_stocks_count);

                        $('#dealing_stocks_count').keyup(update_stocks_count);
                        $('#dealing_stocks_count').change(update_stocks_count);
                        $('#transfer_stocks_dialog').modal();
                    }

                    function send_money() {
                        json_request('transfer-money', {
                            'count': $('#money_transfer_count').val(),
                            'uid':<?= $user->id ?>,
                            'is_anonim': $('#money_transfer_anonym').prop('checked') ? 1 : 0,
                            'is_secret': $('#money_transfer_hidden').prop('checked') ? 1 : 0
                        });
                    }

                    function create_stocks_dealing() {
                        json_request('stocks-dealing', {
                            'holding_id': $('#holding_id').val(),
                            'count': $('#dealing_stocks_count').val(),
                            'cost': $('#dealing_cost').val(),
                            'uid':<?= $user->id ?>
                        });
                    }

                    function public_statement(type) {
                        json_request('public-statement', {'uid':<?= $user->id ?>, 'type': type});
                    }

                </script>
<?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    
    function chooseIdeology() {
        var buttons = '<button class="btn btn-primary" onclick="json_request(\'user/save-ideology\',{ideologyId:$(\'#new-ideology-id\').val()})"><?=Yii::t('app', 'Save')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Cancel')?></button>';
        createAjaxModal('user/choose-ideology', {}, 
            '<?=Yii::t('app', 'Choose your new ideology')?>',
            buttons
        );
    }
    $('#choose-ideology-btn').click(chooseIdeology);
    
    function chooseReligion() {
        var buttons = '<button class="btn btn-primary" onclick="json_request(\'user/save-religion\',{religionId:$(\'#new-religion-id\').val()})"><?=Yii::t('app', 'Save')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Cancel')?></button>';
        createAjaxModal('user/choose-religion', {}, 
            '<?=Yii::t('app', 'Choose your new religion')?>',
            buttons
        );
    }
    $('#choose-religion-btn').click(chooseReligion);

</script>
