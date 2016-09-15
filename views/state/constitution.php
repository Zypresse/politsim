<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\components\MyHtmlHelper,
    app\models\Constitution;

/* @var $this yii\base\View */
/* @var $state app\models\State */
/* @var $constitution app\models\Constitution */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Constitution of {0}', [Html::encode($state->name)])?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::stateLink($state)?></li>
        <li class="active"><?=Yii::t('app', 'Constitution')?></li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <table class="table table-bordered table-condensed">
                <thead></thead>
                <tbody>
                    <tr>
                        <td colspan="2" class="text-center"><h4><?=Yii::t('app', 'Political properties')?></h4></td>
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('partyPolicy')?>:</strong></td>
                        <td><?=[
                            Constitution::PARTY_POLICY_FORBIDDEN => Yii::t('app', 'Parties forbidden'),
                            Constitution::PARTY_POLICY_ALLOW_ONLY_RULING => Yii::t('app', 'Allowed only ruling party'),
                            Constitution::PARTY_POLICY_ALLOW_REGISTERED => Yii::t('app', 'Allowed only currently registered parties'),
                            Constitution::PARTY_POLICY_NEED_CONFIRM => Yii::t('app', 'Parties registration needs goverment confirmation'),
                            Constitution::PARTY_POLICY_FREE => Yii::t('app', 'Free parties registration'),
                        ][$constitution->partyPolicy]?></td>
                    </tr>
                    <?php if ($constitution->rulingParty): ?>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('rulingPartyId')?>:</strong></td>
                        <td><?=LinkCreator::partyLink($constitution->rulingParty)?></td>
                    </tr>
                    <?php endif ?>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('partyRegistrationTax')?>:</strong></td>
                        <td><?=($constitution->partyRegistrationTax)?MyHtmlHelper::moneyFormat($constitution->partyRegistrationTax):Yii::t('app', 'Free')?></td>
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('isAllowMultipost')?>:</strong></td>
                        <td><?=MyHtmlHelper::booleanToYesNo($constitution->isAllowMultipost)?></td>
                    </tr>
                    <?php /*@TODO if ($constitution->leaderPost): ?>
                    <tr>
                        
                    </tr>
                    <?php endif */?>
                    
                    <tr>
                        <td colspan="2" class="text-center"><h4><?=Yii::t('app', 'Economical properties')?></h4></td>
                    </tr>
                    <?php /*@TODO if ($constitution->centralBank): ?>
                    <tr>
                        
                    </tr>
                    <?php endif */?>
                    <?php /*@TODO if ($constitution->currency): ?>
                    <tr>
                        
                    </tr>
                    <?php endif */?>
                    <?php if ($constitution->isAllowSetExchangeRateManually): ?>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('isAllowSetExchangeRateManually')?>:</strong></td>
                        <td><?=MyHtmlHelper::booleanToYesNo($constitution->isAllowSetExchangeRateManually)?></td>                        
                    </tr>
                    <?php endif ?>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('taxBase')?>:</strong></td>
                        <td><?=MyHtmlHelper::zeroOne2Percents($constitution->taxBase)?></td>
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('localBusinessPolicy')?>:</strong></td>
                        <td><?=[
                            Constitution::BUISNESS_FORBIDDEN => Yii::t('app', 'Private companies forbidden'),
                            Constitution::BUISNESS_ALLOW_REGISTERED => Yii::t('app', 'Only currently registered companies allowed'),
                            Constitution::BUISNESS_FREE => Yii::t('app', 'Free companies creation and buisness'),
                        ][$constitution->localBusinessPolicy]?></td>
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('localBusinessRegistrationTax')?>:</strong></td>
                        <td><?=($constitution->localBusinessRegistrationTax)?MyHtmlHelper::moneyFormat($constitution->localBusinessRegistrationTax):Yii::t('app', 'Free')?></td>
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('localBusinessMinCapital')?>:</strong></td>
                        <td><?=($constitution->localBusinessMinCapital)?MyHtmlHelper::moneyFormat($constitution->localBusinessMinCapital):Yii::t('app', 'Unlimited')?></td>                        
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('localBusinessMaxCapital')?>:</strong></td>
                        <td><?=($constitution->localBusinessMaxCapital)?MyHtmlHelper::moneyFormat($constitution->localBusinessMaxCapital):Yii::t('app', 'Unlimited')?></td>                        
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('foreignBusinessPolicy')?>:</strong></td>
                        <td><?=[
                            Constitution::BUISNESS_FORBIDDEN => Yii::t('app', 'Private companies forbidden'),
                            Constitution::BUISNESS_ALLOW_REGISTERED => Yii::t('app', 'Only currently registered companies allowed'),
                            Constitution::BUISNESS_FREE => Yii::t('app', 'Free companies creation and buisness'),
                        ][$constitution->foreignBusinessPolicy]?></td>
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('foreignBusinessRegistrationTax')?>:</strong></td>
                        <td><?=($constitution->foreignBusinessRegistrationTax)?MyHtmlHelper::moneyFormat($constitution->foreignBusinessRegistrationTax):Yii::t('app', 'Free')?></td>
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('foreignBusinessMinCapital')?>:</strong></td>
                        <td><?=($constitution->foreignBusinessMinCapital)?MyHtmlHelper::moneyFormat($constitution->foreignBusinessMinCapital):Yii::t('app', 'Unlimited')?></td>                        
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('foreignBusinessMaxCapital')?>:</strong></td>
                        <td><?=($constitution->foreignBusinessMaxCapital)?MyHtmlHelper::moneyFormat($constitution->foreignBusinessMaxCapital):Yii::t('app', 'Unlimited')?></td>                        
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('npcBusinessPolicy')?>:</strong></td>
                        <td><?=[
                            Constitution::BUISNESS_FORBIDDEN => Yii::t('app', 'Private companies forbidden'),
                            Constitution::BUISNESS_ALLOW_REGISTERED => Yii::t('app', 'Only currently registered companies allowed'),
                            Constitution::BUISNESS_FREE => Yii::t('app', 'Free companies creation and buisness'),
                        ][$constitution->npcBusinessPolicy]?></td>
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('npcBusinessRegistrationTax')?>:</strong></td>
                        <td><?=($constitution->npcBusinessRegistrationTax)?MyHtmlHelper::moneyFormat($constitution->npcBusinessRegistrationTax):Yii::t('app', 'Free')?></td>
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('npcBusinessMinCapital')?>:</strong></td>
                        <td><?=($constitution->npcBusinessMinCapital)?MyHtmlHelper::moneyFormat($constitution->npcBusinessMinCapital):Yii::t('app', 'Unlimited')?></td>                        
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('npcBusinessMaxCapital')?>:</strong></td>
                        <td><?=($constitution->npcBusinessMaxCapital)?MyHtmlHelper::moneyFormat($constitution->npcBusinessMaxCapital):Yii::t('app', 'Unlimited')?></td>                        
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>