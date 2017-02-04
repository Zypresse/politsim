<?php

use app\components\LinkCreator,
    app\models\politics\constitution\articles\postsonly\powers\Licenses;

/* @var $this \yii\web\View */
/* @var $user \app\models\User */
/* @var $post \app\models\politics\AgencyPost */
/* @var $powersLicenses Licenses */

?>

<div class="box">
    <div class="box-header">
        <h4 class="box-title"><?= Yii::t('app', 'Licenses') ?></h4>
    </div>
    <div class="box-body">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
            <?php if ($powersLicenses->isSelected(Licenses::ACCEPT)): ?>
                <li class="active">
                    <a href="#approve-licenses-registration" data-toggle="tab" aria-expanded="true"><?=Yii::t('app', 'Approve licenses')?></a>
                </li>
            <?php endif ?>
            <?php if ($powersLicenses->isSelected(Licenses::REVOKE)): ?>
                <li <?=$powersLicenses->isSelected(Licenses::ACCEPT)?'':'class="active"'?> >
                    <a href="#revoke-licenses-registration" data-toggle="tab" aria-expanded="true"><?=Yii::t('app', 'Revoke licenses')?></a>
                </li>
            <?php endif ?>
            </ul>
            <div class="tab-content">
            <?php if ($powersLicenses->isSelected(Licenses::ACCEPT)): ?>
                <div class="tab-pane active" id="approve-licenses-registration">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Company') ?></th>
                                <th><?= Yii::t('app', 'Company type') ?></th>
                                <th><?= Yii::t('app', 'License type') ?></th>
                                <th><?= Yii::t('app', 'Date Pending') ?></th>
                                <th><?= Yii::t('app', 'Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($post->state->licensesUnconfirmed as $license): ?>
                                <tr>
                                    <td><?= LinkCreator::companyLink($license->company) ?></td>
                                    <td>
                                        <?= $license->company->isGoverment ? Yii::t('app', 'Goverment company') : ($license->company->isHalfGoverment ? Yii::t('app', 'Half-goverment company') : Yii::t('app', 'Private company')) ?>
                                    </td>
                                    <td><?= $license->proto->name ?></td>
                                    <td>
                                        <span class="formatDate" data-unixtime="<?=$license->datePending?>" ><?=date('H:M d.m.Y', $license->datePending)?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-primary btn-confirm-license-registration" data-license-id="<?=$license->id?>" ><i class="fa fa-check"></i> <?= Yii::t('app', 'Approve license') ?></button>
                                            <button class="btn btn-danger btn-revoke-license-registration" data-license-id="<?=$license->id?>" ><i class="fa fa-ban"></i> <?= Yii::t('app', 'Revoke license') ?></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
            <?php if ($powersLicenses->isSelected(Licenses::REVOKE)): ?>
                <div class="tab-pane <?=$powersLicenses->isSelected(Licenses::ACCEPT)?'':'active'?>" id="revoke-licenses-registration">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Company') ?></th>
                                <th><?= Yii::t('app', 'Company type') ?></th>
                                <th><?= Yii::t('app', 'License type') ?></th>
                                <th><?= Yii::t('app', 'Date Granted') ?></th>
                                <th><?= Yii::t('app', 'Date Expired') ?></th>
                                <th><?= Yii::t('app', 'Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($post->state->licenses as $license): ?>
                                <tr>
                                    <td><?= LinkCreator::companyLink($license->company) ?></td>
                                    <td>
                                        <?= $license->company->isGoverment ? Yii::t('app', 'Goverment company') : ($license->company->isHalfGoverment ? Yii::t('app', 'Half-goverment company') : Yii::t('app', 'Private company')) ?>
                                    </td>
                                    <td><?= $license->proto->name ?></td>
                                    <td>
                                    <?php if ($license->dateGranted): ?>
                                        <span class="formatDate" data-unixtime="<?=$license->dateGranted?>" ><?=date('H:M d.m.Y', $license->dateGranted)?></span>
                                    <?php else: ?>
                                        <?=Yii::t('yii', '(not set)')?>
                                    <?php endif ?>
                                    </td>
                                    <td>
                                    <?php if ($license->dateExpired): ?>
                                        <span class="formatDate" data-unixtime="<?=$license->dateExpired?>" ><?=date('H:M d.m.Y', $license->dateExpired)?></span>
                                    <?php else: ?>
                                        <?=Yii::t('yii', '(not set)')?>
                                    <?php endif ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-danger btn-block btn-revoke-license-registration" data-license-id="<?=$license->id?>" ><i class="fa fa-ban"></i> <?= Yii::t('app', 'Revoke license') ?></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
            </div>
        </div>
    </div>
<!--    <div class="box-footer">
        <div class="btn-group">

        </div>
    </div>-->
</div>
<script type="text/javascript">
    
    $('.btn-confirm-license-registration').click(function(){
        var licenseId = $(this).data('licenseId');
        json_request(
            'license/confirm', 
            {postId:<?=$post->id?>, licenseId: licenseId},
            false, false, null, 'POST'
        );
    });
    
    $('.btn-revoke-license-registration').click(function(){
        var licenseId = $(this).data('licenseId');
        json_request(
            'license/revoke', 
            {postId:<?=$post->id?>, licenseId: licenseId},
            false, false, null, 'POST'
        );
    });
    
</script>
