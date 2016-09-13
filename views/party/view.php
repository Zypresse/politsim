<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator,
    app\models\Party,
    app\models\PartyPost;

/* @var $this yii\base\View */
/* @var $party app\models\Party */
/* @var $user app\models\User */

$isHaveMembership = $user->isHaveMembership($party->id);
if ($isHaveMembership) {
    $userPost = $party->getPostByUserId($user->id);
}

?>
<section class="content-header">
    <h1>
        <?=Html::encode($party->name)?>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><?=$party->flag ? Html::img($party->flag, ['style' => 'height: 10px; vertical-align: baseline;']) : ''?> <?=Html::encode($party->name)?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <?php if ($party->flag || $party->anthem): ?>
        <div class="col-md-4">
            <?php if ($party->flag): ?>
            <div class="box">
                <div class="box-body">
                    <?=Html::img($party->flag, ['class' => 'img-polaroid', 'style' => 'width: 100%'])?>
                    <div class="photo_bottom_container">
                        <span class="star" ><?= $party->fame ?> <?= MyHtmlHelper::icon('star') ?></span>
                        <span class="heart" ><?= $party->trust?> <?= MyHtmlHelper::icon('heart') ?></span>
                        <span class="chart_pie" ><?= $party->success ?> <?= MyHtmlHelper::icon('chart_pie') ?></span>
                    </div>
                </div>
                <div class="box-footer">
                    <em><?=Yii::t('app', 'Party flag')?></em>
                </div>
            </div>
            <?php endif ?>
            <?php if ($party->anthem): ?>
                <div class="box">
                    <div class="box-body">
                        <iframe id="sc-widget" src="https://w.soundcloud.com/player/?url=<?= $party->anthem ?>" width="100%" height="100" scrolling="no" frameborder="no"></iframe>
                    </div>
                    <div class="box-footer">
                        <em><?=Yii::t('app', 'Party anthem')?></em>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <?php endif ?>
        <div class="col-md-<?=($party->flag || $party->anthem)?8:12?>">
            <div class="box">
                <div class="box-header">
                    <h1>
                        <?=Html::encode($party->name)?>
                         <small>(<?=Html::encode($party->nameShort)?>)</small>
                    </h1>
                </div>
                <div class="box-body">
                    <p><?=Yii::t('app', 'It`s a party of state ')?><?=LinkCreator::stateLink($party->state)?></p>
                    <p><strong><i class="fa fa-flag"></i> <?=Yii::t('app', 'Ideology')?>:</strong> <?=$party->ideology->name?></p>
                    <p><strong><i class="fa fa-sign-in"></i> <?=Yii::t('app', 'Joining')?>:</strong> <?=[
                        Party::JOINING_RULES_PRIVATE => Yii::t('app', 'Private'),
                        Party::JOINING_RULES_CLOSED => Yii::t('app', 'Closed'),
                        Party::JOINING_RULES_OPEN => Yii::t('app', 'Open'),
                    ][$party->joiningRules]?></p>
                    <p><strong><i class="fa fa-list-alt"></i> <?=Yii::t('app', 'Election list creation')?>:</strong> <?=[
                        Party::LIST_CREATION_RULES_LEADER => Yii::t('app', 'By leader'),
                        Party::LIST_CREATION_RULES_PRIMARIES => Yii::t('app', 'By primaries'),
                    ][$party->listCreationRules]?></p>
                    <?php if ($party->dateDeleted): ?>
                    <div class="callout callout-danger">
                        <h4><i class="icon fa fa-ban"></i> <?=Yii::t('app', 'Party deleted!')?></h4>

                        <p><?=Yii::t('app', 'This party has been deleted')?> <?=MyHtmlHelper::timeAutoFormat($party->dateDeleted)?></p>
                    </div>
                    <?php else: ?>
                        <?php if ($party->leaderPost && $party->leaderPost->user): ?>
                        <p>
                            <strong><?=Html::encode($party->leaderPost->name)?>:</strong> <?=LinkCreator::userLink($party->leaderPost->user) ?>
                        </p>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3><?=Yii::t('app', 'Party posts')?></h3>
                    <?php if ($isHaveMembership && $userPost && ($userPost->powers & PartyPost::POWER_EDIT_POSTS)): ?>
                    <div class="box-tools pull-right">
                        <button id="create-new-post-btn" class="btn btn-sm btn-success">
                            <i class="fa fa-plus"></i> <?=Yii::t('app', 'Create')?>
                        </button>
                    </div>
                    <?php endif ?>
                </div>
                <div class="box-body">
                    <table class="table table-condensed table-bordered table-hover">
                        <thead>

                        </thead>
                        <tbody>
                            <?php foreach ($party->posts as $post): ?>
                            <tr>
                                <td><?=Html::encode($post->name)?></td>
                                <td><?=($post->user) ? LinkCreator::userLink($post->user) : Yii::t('app', 'Not set')?></td>
                                <td><?=[
                                    PartyPost::APPOINTMENT_TYPE_LEADER => Yii::t('app', 'By leader'),
                                    PartyPost::APPOINTMENT_TYPE_INHERITANCE => Yii::t('app', 'By inheritance'),
                                    PartyPost::APPOINTMENT_TYPE_PRIMARIES => Yii::t('app', 'By primaries'),
                                ][$post->appointmentType]?>
                                </td>
                                <?php if ($isHaveMembership && $userPost && ($userPost->powers & PartyPost::POWER_EDIT_POSTS)): ?>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button data-post-id="<?=$post->id?>" class="edit-party-post-btn btn btn-xs btn-info"><i class="fa fa-edit"></i> <?=Yii::t('app', 'Edit')?></button>
                                        <?php if ($post->id != $userPost->id): ?>
                                        <?php if ($post->user): ?>
                                            <button data-post-id="<?=$post->id?>" class="drop-party-post-btn btn btn-xs btn-warning"><i class="fa fa-ban"></i> <?=Yii::t('app', 'Drop')?></button>
                                        <?php endif ?>
                                        <button data-post-id="<?=$post->id?>" class="delete-party-post-btn btn btn-xs btn-danger"><i class="fa fa-trash"></i> <?=Yii::t('app', 'Delete')?></button>
                                        <?php endif ?>
                                    </div>
                                </td>
                                <?php endif ?>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>                
            </div>
            <div class="box">
                <div class="box-header">
                    <h3><?=Yii::t('app', 'Available actions')?></h3>
                </div>
                <div class="box-body">
                    <p>
                        <?php if ($isHaveMembership):?>
                            <?=Yii::t('app','You have this party membership')?><br>
                            <?php if ($userPost):?>
                                <?=Yii::t('app','You are {0} of this party', [Html::encode($userPost->name)])?><br>
                            <?php endif ?>
                        <?php endif ?>
                    </p>
                    <div class="btn-group">
                        <?php if ($isHaveMembership):?>
                            <button onclick="if (confirm('<?=Yii::t('app', 'Are you sure?')?>')) json_request('membership/cancel', {partyId: <?=$party->id?>})" class="btn btn-danger"><?=Yii::t('app', 'Fire membership')?></button>
                            <?php if ($userPost):?>
                                <?php if ($userPost->appointmentType == PartyPost::APPOINTMENT_TYPE_INHERITANCE):?>
                                <button class="btn btn-primary"><?=Yii::t('app', 'Set successor')?></button>
                                <?php endif ?>
                            <?php endif ?>
                        <?php else: ?>
                            <?php if (!$party->dateDeleted): ?>
                                <button onclick="json_request('membership/request', {partyId: <?=$party->id?>})" class="btn btn-primary"><?=Yii::t('app', 'Make request for membership')?></button>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    
    function createNewPost() {
        var buttons = '<button class="btn btn-primary" onclick="$(\'#create-party-post-form\').yiiActiveForm(\'submitForm\')"><?=Yii::t('app', 'Create')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Cancel')?></button>';
        createAjaxModal('party/create-post-form', {partyId: <?=$party->id?>}, 
            '<?=Yii::t('app', 'Create new party post')?>',
            buttons
        );
    }
    $('#create-new-post-btn').click(createNewPost);
    
    function editPost() {
        var buttons = '<button class="btn btn-primary" onclick="$(\'#edit-party-post-form\').yiiActiveForm(\'submitForm\')"><?=Yii::t('app', 'Save')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Cancel')?></button>';
        createAjaxModal('party/edit-post-form', {postId: $(this).data('postId')}, 
            '<?=Yii::t('app', 'Edit party post')?>',
            buttons
        );
    }
    $('.edit-party-post-btn').click(editPost);
    
    function deletePost() {
        if (confirm('<?=Yii::t('app', 'Are you sure?')?>')) {
            json_request('party/delete-post', {id: $(this).data('postId')});
        }
    }
    $('.delete-party-post-btn').click(deletePost);
    
</script>
