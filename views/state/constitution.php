<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\Powers;

/* @var $this yii\base\View */
/* @var $state app\models\politics\State */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Constitution of {0}', [Html::encode($state->name)])?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::stateLink($state)?></li>
        <li class="active"><i class="fa fa-list-alt"></i> <?=Yii::t('app', 'Constitution')?></li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#state-constitution" data-toggle="tab" aria-expanded="true"><?=Yii::t('app', 'Main articles')?></a></li>
                    <?php foreach ($state->agencies as $agency): ?>
                    <li><a href="#agency-constitution-<?=$agency->id?>" data-toggle="tab" aria-expanded="false"><?=Yii::t('app', 'About {0}', [Html::encode($agency->nameShort)])?></a></li>
                    <?php endforeach ?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="state-constitution">
                        <table class="table table-bordered table-condensed">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th colspan="2" class="text-center"><h4><?=Yii::t('app', 'Political properties')?></h4></th>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'State leader')?></th>
                                    <td><?=$state->constitution->getArticleByType(ConstitutionArticleType::LEADER_POST)->post->name?></td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Allow more than one agency post to user')?></th>
                                    <td><?=$state->constitution->getArticleByType(ConstitutionArticleType::MULTIPOST)->name?></td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Party politics')?></th>
                                    <td><?=$state->constitution->getArticleByType(ConstitutionArticleType::PARTIES)->name?></td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Allow more than one party membership to user')?></th>
                                    <td><?=$state->constitution->getArticleByType(ConstitutionArticleType::MULTIMEMBERSHIP)->name?></td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Bills voting terms')?></th>
                                    <td><?=MyHtmlHelper::formateNumberword($state->constitution->getArticleByType(ConstitutionArticleType::BILLS)->value, 'часов', 'час', 'часа')?></td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Official currency')?></th>
                                    <td><?=$state->constitution->getArticleByType(ConstitutionArticleType::CURRENCY)->name?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php foreach ($state->agencies as $agency): ?>
                    <div class="tab-pane" id="agency-constitution-<?=$agency->id?>">
                        <table class="table table-bordered table-condensed">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th colspan="2" class="text-center"><h4><?=Yii::t('app', 'Political properties')?></h4></th>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Agency leader')?></th>
                                    <td><?=$agency->constitution->getArticleByType(ConstitutionArticleType::LEADER_POST)->post->name?></td>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-center"><h4><?=Yii::t('app', 'Agency posts')?></h4></th>
                                </tr>
                                <?php foreach ($agency->posts as $post): ?>
                                <tr>
                                    <th colspan="2" class="text-center"><h5><?=Html::encode($post->name)?></h5></th>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Destignation type')?></th>
                                    <td><?=$post->constitution->getArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE)->name?></td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Terms of office')?></th>
                                    <td><?=$post->constitution->getArticleByType(ConstitutionArticleType::TERMS_OF_OFFICE)->value?></td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Terms of elections')?></th>
                                    <td><?=$post->constitution->getArticleByType(ConstitutionArticleType::TERMS_OF_ELECTION)->name?></td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Bills powers')?></th>
                                    <td><?=$post->constitution->getArticleByType(ConstitutionArticleType::POWERS, Powers::BILLS)->name?></td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Parties powers')?></th>
                                    <td><?=$post->constitution->getArticleByType(ConstitutionArticleType::POWERS, Powers::PARTIES)->name?></td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</section>