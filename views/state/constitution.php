<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\components\MyHtmlHelper,
    app\models\StateConstitution,
    app\models\AgencyConstitution;

/* @var $this yii\base\View */
/* @var $state app\models\State */
/* @var $constitution app\models\StateConstitution */
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
            <table class="table table-bordered table-condensed">
                <thead></thead>
                <tbody>
                    <tr>
                        <td colspan="2" class="text-center"><h4><?=Yii::t('app', 'Political properties')?></h4></td>
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('partyPolicy')?>:</strong></td>
                        <td><?=[
                            StateConstitution::PARTY_POLICY_FORBIDDEN => Yii::t('app', 'Parties forbidden'),
                            StateConstitution::PARTY_POLICY_ALLOW_ONLY_RULING => Yii::t('app', 'Allowed only ruling party'),
                            StateConstitution::PARTY_POLICY_ALLOW_REGISTERED => Yii::t('app', 'Allowed only currently registered parties'),
                            StateConstitution::PARTY_POLICY_NEED_CONFIRM => Yii::t('app', 'Parties registration needs goverment confirmation'),
                            StateConstitution::PARTY_POLICY_FREE => Yii::t('app', 'Free parties registration'),
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
                        <td><strong><?=$constitution->getAttributeLabel('regionsLeadersAssignmentRule')?>:</strong></td>
                        <td>
                            <?=[
                                AgencyConstitution::ASSIGNMENT_RULE_NOT_SET => Yii::t('app', 'Assignment type not set'),
                                AgencyConstitution::ASSIGNMENT_RULE_BY_STATE_LEADER => Yii::t('app', 'Set by state leader'),
                                AgencyConstitution::ASSIGNMENT_RULE_INHERITANCE => Yii::t('app', 'By inheritance'),
                                AgencyConstitution::ASSIGNMENT_RULE_ELECTIONS_PLURARITY => Yii::t('app', 'By plurarity voting'),
                            ][$constitution->regionsLeadersAssignmentRule]?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('religion')?>:</strong></td>
                        <td><?=($constitution->religion)?$constitution->religion->name:Yii::t('app', 'Not set')?></td>                        
                    </tr>
                    
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
                        <td><strong><?=$constitution->getAttributeLabel('businessPolicy')?>:</strong></td>
                        <td>
                            <?=[
                                StateConstitution::BUISNESS_FORBIDDEN_ALL => Yii::t('app', 'Private companies forbidden'),
                                StateConstitution::BUISNESS_ALLOW_REGISTERED_ALL => Yii::t('app', 'Only currently registered companies allowed'),
                                StateConstitution::BUISNESS_FREE_ALL => Yii::t('app', 'Free companies creation and buisness'),
                                StateConstitution::BUISNESS_FORBIDDEN_FOREIGN_ALLOW_REGISTERED_LOCAL => Yii::t('app', 'Only currently registered local companies allowed, foreign forbidden'),
                                StateConstitution::BUISNESS_FORBIDDEN_FOREIGN_FREE_LOCAL => Yii::t('app', 'Free local companies creation and buisness, foreign forbidden'),
                                StateConstitution::BUISNESS_ALLOW_REGISTERED_FOREIGN_FREE_LOCAL => Yii::t('app', 'Only currently registered foreign companies allowed, free local companies creation and buisness'),
                            ][$constitution->businessPolicy]?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('retirementAge')?>:</strong></td>
                        <td><?=($constitution->retirementAge)?$constitution->retirementAge:Yii::t('app', 'Not set')?></td>                        
                    </tr>
                    <tr>
                        <td><strong><?=$constitution->getAttributeLabel('minWage')?>:</strong></td>
                        <td><?=($constitution->minWage)?MyHtmlHelper::moneyFormat($constitution->minWage):Yii::t('app', 'Not set')?></td>                        
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>