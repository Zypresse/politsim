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
$userPost = null;
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
                    <?php if ($party->dateDeleted): ?>
                    <div class="callout callout-danger">
                        <h4><i class="icon fa fa-ban"></i> <?=Yii::t('app', 'Party deleted!')?></h4>

                        <p><?=Yii::t('app', 'This party has been deleted')?> <?=MyHtmlHelper::timeAutoFormat($party->dateDeleted)?></p>
                    </div>
                    <?php endif ?>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <table class="table table-condensed table-bordered">
                                <thead>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong><i class="fa fa-building"></i> <?=Yii::t('app', 'State')?>:</strong></td>
                                        <td><?=LinkCreator::stateLink($party->state)?></td>
                                    </tr>
                                    <tr>
                                        <td><strong><i class="fa fa-flag"></i> <?=Yii::t('app', 'Ideology')?>:</strong></td>
                                        <td><?=$party->ideology->name?></td>
                                    </tr>
                                    <tr>
                                        <td><strong><i class="fa fa-sign-in"></i> <?=Yii::t('app', 'Joining')?>:</strong></td>
                                        <td>
                                            <?=[
                                                Party::JOINING_RULES_PRIVATE => Yii::t('app', 'Private'),
                                                Party::JOINING_RULES_CLOSED => Yii::t('app', 'Closed'),
                                                Party::JOINING_RULES_OPEN => Yii::t('app', 'Open'),
                                            ][$party->joiningRules]?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong><i class="fa fa-list-alt"></i> <?=Yii::t('app', 'Election list creation')?>:</strong></td>
                                        <td>
                                            <?=[
                                                Party::LIST_CREATION_RULES_LEADER => Yii::t('app', 'By leader'),
                                                Party::LIST_CREATION_RULES_PRIMARIES => Yii::t('app', 'By primaries'),
                                            ][$party->listCreationRules]?>
                                        </td>
                                    </tr>
                                    <?php if (!$party->dateDeleted): ?>
                                        <?php if ($party->leaderPost && $party->leaderPost->user): ?>
                                        <tr>
                                            <td><strong><i class="fa fa-user"></i> <?=Html::encode($party->leaderPost->name)?>:</strong></td>
                                            <td><?=LinkCreator::userLink($party->leaderPost->user) ?></td>
                                        </tr>
                                        <?php endif ?>
                                    <?php endif ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <table class="table">
                                <thead>
                                </thead>
                                <tbody>                                    
                                    <tr>
                                        <td><strong><i class="fa fa-group"></i> <?=Yii::t('app', 'Party members')?>:</strong></td>
                                        <td><?=MyHtmlHelper::formateNumberword($party->membersCount, 'h')?></td>
                                    </tr>
                                    <tr>
                                        <td><strong><i class="fa fa-sign-in"></i> <?=Yii::t('app', 'Membership requests')?>:</strong></td>
                                        <td><?=MyHtmlHelper::formateNumberword($party->getRequestedMemberships()->count(), 'заявок', 'заявка', 'заявки')?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="btn-group text-center">
                                                <?php if ($userPost && $userPost->powers & PartyPost::POWER_APPROVE_REQUESTS): ?>
                                                <button class="btn btn-primary"><i class="fa fa-sign-in"></i> <?=Yii::t('app', 'Manage membership requests')?></button>
                                                <?php endif ?>
                                                <a href="#!party/members&id=<?=$party->id?>" class="btn btn-info"><i class="fa fa-group"></i> <?=Yii::t('app', 'Look members list')?></a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                            <tr>
                                <th><?=Yii::t('app', 'Post name')?></th>
                                <th><?=Yii::t('app', 'User')?></th>
                                <th><?=Yii::t('app', 'Appointment type')?></th>
                                <?php if ($isHaveMembership && $userPost && ($userPost->powers & PartyPost::POWER_EDIT_POSTS)): ?>
                                <th><?=Yii::t('app', 'Actions')?></th>
                                <?php endif ?>
                            </tr>
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
                                        <?php if ($post->appointmentType == PartyPost::APPOINTMENT_TYPE_LEADER): ?>
                                            <button data-post-id="<?=$post->id?>" class="set-party-post-btn btn btn-xs btn-primary"><i class="fa fa-user"></i> <?=Yii::t('app', 'Set')?></button>
                                        <?php endif ?>    
                                        <?php if ($post->user && $post->appointmentType == PartyPost::APPOINTMENT_TYPE_LEADER): ?>
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
                                <button id="set-successor-btn" class="btn btn-primary"><?=Yii::t('app', 'Set successor')?></button>
                                <?php endif ?>
                                <button id="self-drop-party-post-btn" data-post-id="<?=$userPost->id?>" class="btn btn-warning"><?=Yii::t('app', 'Drop self from post')?></button>
                                <?php if ($userPost->powers & PartyPost::POWER_CHANGE_FIELDS): ?>
                                <button id="edit-party-btn" class="btn btn-primary"><?=Yii::t('app', 'Edit party parametres')?></button>
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
<?php if ($isHaveMembership && $userPost): ?>
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
    
    
    function setSuccessor() {
        var buttons = '<button class="btn btn-primary" onclick="if ($(\'#successor-user-id\').val()) json_request(\'party/set-successor\',{postId: <?=$userPost->id?>, userId: $(\'#successor-user-id\').val()})"><?=Yii::t('app', 'Save')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Cancel')?></button>';
        createAjaxModal('party/set-successor-form', {postId: <?=$userPost->id?>}, 
            '<?=Yii::t('app', 'Set successor')?>',
            buttons
        );
    }
    $('#set-successor-btn').click(setSuccessor);
    
    function setPost() {
        var buttons = '<button class="btn btn-primary" onclick="if ($(\'#set-party-post-user-id\').val()) json_request(\'party/set-post\',{postId: '+$(this).data('postId')+', userId: $(\'#set-party-post-user-id\').val()})"><?=Yii::t('app', 'Save')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Cancel')?></button>';
        createAjaxModal('party/set-post-form', {postId: $(this).data('postId')}, 
            '<?=Yii::t('app', 'Set user to party post')?>',
            buttons
        );
    }
    $('.set-party-post-btn').click(setPost);
    
    function dropPost() {
        if (confirm('<?=Yii::t('app', 'Are you sure?')?>')) {
            json_request('party/drop-post', {id: $(this).data('postId')});
        }
    }
    $('.drop-party-post-btn').click(dropPost);
    $('#self-drop-party-post-btn').click(dropPost);
    
    function editParty() {
        var buttons = '<button class="btn btn-primary" onclick="$(\'#edit-form\').yiiActiveForm(\'submitForm\')"><?=Yii::t('app', 'Save')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Cancel')?></button>';
        createAjaxModal('party/edit-form', {id: <?=$party->id?>}, 
            '<?=Yii::t('app', 'Edit party parametres')?>',
            buttons
        );
    }
    $('#edit-party-btn').click(editParty);
    
</script>
<?php endif ?>
