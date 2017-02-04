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
                                    <td>
                                        <?php
                                            $article = $state->constitution->getArticleByType(ConstitutionArticleType::LEADER_POST);
                                        ?>
                                        <?=$article ? Html::encode($article->post->name) : Yii::t('yii', '(not set)')?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Allow more than one agency post to user')?></th>
                                    <td>
                                        <?php
                                            $article = $state->constitution->getArticleByType(ConstitutionArticleType::MULTIPOST);
                                        ?>
                                        <?=$article ? $article->name : Yii::t('yii', '(not set)')?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Party politics')?></th>
                                    <td>
                                        <?php
                                            $article = $state->constitution->getArticleByType(ConstitutionArticleType::PARTIES)
                                        ?>
                                        <?=$article ? $article->name : Yii::t('yii', '(not set)')?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Allow more than one party membership to user')?></th>
                                    <td>
                                        <?php
                                            $article = $state->constitution->getArticleByType(ConstitutionArticleType::MULTIMEMBERSHIP)
                                        ?>
                                        <?=$article ? $article->name : Yii::t('yii', '(not set)')?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Bills voting terms')?></th>
                                    <td>
                                        <?php
                                            $article = $state->constitution->getArticleByType(ConstitutionArticleType::BILLS);
                                        ?>
                                        <?=$article ? MyHtmlHelper::formateNumberword($article->value, 'часов', 'час', 'часа') : Yii::t('yii', '(not set)')?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Business allowed')?></th>
                                    <td>
                                        <?php
                                            $article = $state->constitution->getArticleByType(ConstitutionArticleType::BUSINESS);
                                        ?>
                                        <?=$article ? $article->name : Yii::t('yii', '(not set)')?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Official currency')?></th>
                                    <td>
                                        <?php
                                            $article = $state->constitution->getArticleByType(ConstitutionArticleType::CURRENCY);
                                        ?>
                                        <?=$article ? $article->name : Yii::t('yii', '(not set)')?>
                                    </td>
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
                                    <td>
                                        <?php
                                            $article = $agency->constitution->getArticleByType(ConstitutionArticleType::LEADER_POST);
                                        ?>
                                        <?=$article ? Html::encode($article->post->name) : Yii::t('yii', '(not set)')?>
                                    </td>
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
                                    <td>
                                        <?php
                                            $article = $post->constitution->getArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE);
                                        ?>
                                        <?=$article ? $article->name : Yii::t('yii', '(not set)')?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Terms of office')?></th>
                                    <td>
                                        <?php
                                            $article = $post->constitution->getArticleByType(ConstitutionArticleType::TERMS_OF_OFFICE);
                                        ?>
                                        <?=$article ? MyHtmlHelper::formateNumberword($article->value, 'дней', 'день', 'дня') : Yii::t('yii', '(not set)')?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Terms of elections')?></th>
                                    <td>
                                        <?php
                                            $article = $post->constitution->getArticleByType(ConstitutionArticleType::TERMS_OF_ELECTION);
                                        ?>
                                        <?=$article ? $article->name : Yii::t('yii', '(not set)')?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Bills powers')?></th>
                                    <td>
                                        <?php
                                            $article = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, Powers::BILLS);
                                        ?>
                                        <?=$article ? $article->name : Yii::t('yii', '(not set)')?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?=Yii::t('app', 'Parties powers')?></th>
                                    <td>
                                        <?php
                                            $article = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, Powers::PARTIES);
                                        ?>
                                        <?=$article ? $article->name : Yii::t('yii', '(not set)')?>
                                    </td>
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