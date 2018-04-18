<?php

use app\helpers\Html;
use app\helpers\LinkCreator;
use app\helpers\Icon;

/* @var $this yii\base\View */
/* @var $model \app\models\politics\Organization */
/* @var $user app\models\auth\User */

$isHaveMembership = $user->isHaveMembership($model->id);
$isHaveMembershipRequest = $user->isHaveMembershipRequest($model->id);
//$userPost = null;
//if ($isHaveMembership) {
//    $userPost = $party->getPostByUserId($user->id);
//}
?>
<section class="content-header">
    <h1>
        <?=Html::encode($model->name)?>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><?=$model->flag ? Html::img($model->flag, ['style' => 'height: 10px; vertical-align: baseline;']) : ''?> <?=Html::encode($model->name)?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <?php if ($model->flag || $model->anthem): ?>
        <div class="col-md-4">
            <?php if ($model->flag): ?>
            <div class="box">
                <div class="box-body">
                    <?=Html::img($model->flag, ['class' => 'img-polaroid', 'style' => 'width: 100%'])?>
                    <?php /*<div class="photo_bottom_container">
                        <span class="star" ><?= $model->fame ?> <?= MyHtmlHelper::icon('star') ?></span>
                        <span class="heart" ><?= $model->trust?> <?= MyHtmlHelper::icon('heart') ?></span>
                        <span class="chart_pie" ><?= $model->success ?> <?= MyHtmlHelper::icon('chart_pie') ?></span>
                    </div> */?>
                </div>
                <div class="box-footer">
                    <em>Флаг организации</em>
                </div>
            </div>
            <?php endif ?>
            <?php if ($model->anthem): ?>
                <div class="box">
                    <div class="box-body">
                        <iframe id="sc-widget" src="https://w.soundcloud.com/player/?url=<?= $model->anthem ?>" width="100%" height="100" scrolling="no" frameborder="no"></iframe>
                    </div>
                    <div class="box-footer">
                        <em>Гимн организации</em>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <?php endif ?>
        <div class="col-md-<?=($model->flag || $model->anthem)?8:12?>">
            <div class="box">
                <div class="box-header">
                    <h1>
                        <?=Html::encode($model->name)?>
                         <small>(<?=Html::encode($model->nameShort)?>)</small>
                    </h1>
                </div>
                <div class="box-body">
                    <?php if ($model->isDeleted): ?>
                    <div class="callout callout-danger">
                        <h4><i class="icon fa fa-ban"></i> Организация расформирована!</h4>

                        <p>Эта организация была расформирована <?=Html::timeAutoFormat($model->dateDeleted)?></p>
                    </div>
                    <?php endif ?>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <table class="table table-condensed table-bordered">
                                <thead>
                                </thead>
                                <tbody>
                                    <?php /*<tr>
                                        <td><strong><i class="fa fa-building"></i> <?=Yii::t('app', 'State')?>:</strong></td>
                                        <td><?=LinkCreator::stateLink($party->state)?></td>
                                    </tr> */?>
                                    <tr>
                                        <td><strong><i class="fa fa-flag"></i> <?=Yii::t('app', 'Ideology')?>:</strong></td>
                                        <td><?=$model->ideology->name?></td>
                                    </tr>
                                    <?php /*<tr>
                                        <td><strong><i class="fa fa-sign-in"></i> <?=Yii::t('app', 'Joining')?>:</strong></td>
                                        <td>
                                            <?=[
                                                Party::JOINING_RULES_PRIVATE => Yii::t('app', 'Private'),
                                                Party::JOINING_RULES_CLOSED => Yii::t('app', 'Closed'),
                                                Party::JOINING_RULES_OPEN => Yii::t('app', 'Open'),
                                            ][$party->joiningRules]?>
                                        </td>
                                    </tr>*/ ?>
                                    <?php /*<tr>
                                        <td><strong><i class="fa fa-list-ul"></i> <?=Yii::t('app', 'Election list creation')?>:</strong></td>
                                        <td>
                                            <?=[
                                                Party::LIST_CREATION_RULES_LEADER => Yii::t('app', 'By leader'),
                                                Party::LIST_CREATION_RULES_PRIMARIES => Yii::t('app', 'By primaries'),
                                            ][$party->listCreationRules]?>
                                        </td>
                                    </tr>*/?>
                                    <?php if (!$model->dateDeleted): ?>
                                        <?php if ($model->leader): ?>
                                        <tr>
                                            <td><strong><i class="fa fa-user"></i> Лидер организации:</strong></td>
                                            <td><?=LinkCreator::userLink($model->leader) ?></td>
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
                                        <td><strong><i class="fa fa-group"></i> Участники организации:</strong></td>
                                        <td>
                                            <?=Html::numberWord($model->getUsers()->count(), 'h')?> 
                                            <a href="/organization/members?id=<?=$model->id?>" class="btn btn-info btn-xs"><i class="fa fa-group"></i> <?=Yii::t('app', 'Full list')?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong><i class="fa fa-sign-in"></i> <?=Yii::t('app', 'Membership requests')?>:</strong></td>
                                        <td>
                                            <?=Html::numberWord($model->getOrganizationMemberships()->where(['dateApproved' => null])->count(), 'заявок', 'заявка', 'заявки')?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong><i class="fa fa-list-alt"></i> <?=Yii::t('app', 'Political program')?>:</strong></td>
                                        <td>
                                            <a href="/organization/program?id=<?=$model->id?>" class="btn btn-info btn-xs"><i class="fa fa-list-alt"></i> <?=Yii::t('app', 'Read')?></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php /*
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
             */ ?>
            <div class="box">
                <div class="box-header">
                    <h3><?=Yii::t('app', 'Top party members')?></h3>
                    <div class="box-tools pull-right">
                        <a href="/organization/members?id=<?=$model->id?>" class="btn btn-info"><i class="fa fa-group"></i> <?=Yii::t('app', 'Full list')?></a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <?php 
                        $colors = ['green-gradient', 'aqua-gradient', 'red-gradient'];
                        for ($i = 0; $i < min([$model->getUsers()->count(),3]); $i++ ):
                            $member = $party->members[$i];
                            $post = $party->getPostByUserId($member->id);
                        ?>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="box box-widget widget-user">
                                <div class="widget-user-header bg-<?=$colors[$i]?>">
                                    <h3 class="widget-user-username"><a href="/user/profile?id=<?=$member->id?>"><?=Html::encode($member->name)?></a></h3>
                                    <?php /*<h5 class="widget-user-desc"><?=$post ? Html::encode($post->name) : Yii::t('app', 'Party member')?></h5> */ ?>
                                </div>
                                <div class="widget-user-image">
                                    <a href="/user/profile?id=<?=$member->id?>"><?=Html::img(Html::encode($member->avatar),['class' => 'img-circle'])?></a>
                                </div>
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-xs-4 border-right">
                                            <div class="description-block">
                                                <h5 class="description-header"><?=$member->fame?> <?=Icon::draw(Icon::STAR)?></h5>
                                                <span class="description-text"><?=Yii::t('app', 'Fame')?></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-4 border-right">
                                            <div class="description-block">
                                                <h5 class="description-header"><?=$member->trust?> <?=Icon::draw(Icon::HEART)?></h5>
                                                <span class="description-text"><?=Yii::t('app', 'Trust')?></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="description-block">
                                                <h5 class="description-header"><?=$member->success?> <?=Icon::draw(Icon::CHARTPIE)?></h5>
                                                <span class="description-text"><?=Yii::t('app', 'Success')?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endfor ?>
                    </div>
                </div>
            </div>
            <?php /*
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
                        <?php if ($isHaveMembershipRequest):?>
                            <?=Yii::t('app', 'You sended request for membership')?>
                        <?php endif ?>
                    </p>
                    <div class="btn-group">
                        <?php if ($isHaveMembership):?>
                            <button onclick="if (confirm('<?=Yii::t('app', 'Are you sure?')?>')) json_request('membership/cancel', {partyId: <?=$party->id?>})" class="btn btn-danger"><i class="fa fa-ban"></i> <?=Yii::t('app', 'Fire membership')?></button>
                            <?php if ($userPost):?>
                                <?php if ($userPost->appointmentType == PartyPost::APPOINTMENT_TYPE_INHERITANCE):?>
                                <button id="set-successor-btn" class="btn btn-primary"><i class="fa fa-user"></i> <?=Yii::t('app', 'Set successor')?></button>
                                <?php endif ?>
                                <button id="self-drop-party-post-btn" data-post-id="<?=$userPost->id?>" class="btn btn-warning"><i class="fa fa-sign-out"></i> <?=Yii::t('app', 'Drop self from post')?></button>
                                <?php if ($userPost->powers & PartyPost::POWER_CHANGE_FIELDS): ?>
                                <button id="edit-party-btn" class="btn btn-primary"><i class="fa fa-cog"></i> <?=Yii::t('app', 'Edit party parametres')?></button>
                                <button id="edit-party-text-btn" class="btn btn-info"><i class="fa fa-list-alt"></i> <?=Yii::t('app', 'Edit party text')?></button>
                                <?php endif ?>
                                <?php if ($userPost && $userPost->powers & PartyPost::POWER_APPROVE_REQUESTS): ?>
                                    <button id="manage-membership-requests-btn" class="btn btn-primary"><i class="fa fa-sign-in"></i> <?=Yii::t('app', 'Manage membership requests')?></button>
                                <?php endif ?>
                            <?php endif ?>
                        <?php else: ?>
                            <?php if (!$party->dateDeleted && $party->joiningRules != Party::JOINING_RULES_PRIVATE && $user->isHaveCitizenship($party->stateId) && !$isHaveMembershipRequest): ?>
                                <button onclick="json_request('membership/request', {partyId: <?=$party->id?>})" class="btn btn-primary"><?=Yii::t('app', 'Make request for membership')?></button>
                            <?php endif ?>
                        <?php if ($isHaveMembershipRequest): ?>
                            <button onclick="json_request('membership/cancel', {partyId: <?=$party->id?>})" class="btn btn-danger"><?=Yii::t('app', 'Cancel membership request')?></button>
                        <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>
             */ ?>
        </div>
    </div>
</section>
