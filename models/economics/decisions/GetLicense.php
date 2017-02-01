<?php

namespace app\models\economics\decisions;

use Yii,
    app\components\LinkCreator,
    app\models\economics\CompanyDecision,
    app\models\economics\CompanyDecisionProto,
    app\models\politics\State,
    app\models\politics\LicenseRule,
    app\models\economics\License,
    app\models\economics\LicenseProto,
    app\models\economics\LicenseProtoType,
    app\models\politics\constitution\ConstitutionArticleType;

/**
 * 
 */
final class GetLicense extends CompanyDecisionProto
{
    
    /**
     * 
     * @param CompanyDecision $decision
     */
    public function accept(CompanyDecision $decision): bool
    {
        $state = State::findByPk($decision->dataArray['stateId']);
        return $state->getNewLicense($decision->companyId, $decision->dataArray['protoId']);
    }

    /**
     * 
     * @param CompanyDecision $decision
     */
    public function render(CompanyDecision $decision): string
    {
        $state = State::findByPk($decision->dataArray['stateId']);
        $proto = LicenseProto::findOne($decision->dataArray['protoId']);
        return Yii::t('app', 'Get new license for «{0}» in state {1}',[
            $proto->name,
            LinkCreator::stateLink($state),
        ]);
    }

    /**
     * 
     * @param CompanyDecision $decision
     */
    public function validate(CompanyDecision $decision): bool
    {
        
        if (!isset($decision->dataArray['stateId']) || !$decision->dataArray['stateId']) {
            $decision->addError('dataArray[stateId]', Yii::t('app', 'State is required field'));
        } else {
            $state = State::findByPk($decision->dataArray['stateId']);
            if (is_null($state) || $state->dateDeleted) {
                $decision->addError('dataArray[stateId]', Yii::t('app', 'Invalid state'));
            }
            
            if (!isset($decision->dataArray['protoId']) || !$decision->dataArray['protoId']) {
                $decision->addError('dataArray[protoId]', Yii::t('app', 'License type is required field'));
            } elseif (!LicenseProto::exist($decision->dataArray['protoId'])) {
                $decision->addError('dataArray[protoId]', Yii::t('app', 'Invalid license type'));
            } else {
                $licenseRule = $state->getLicenseRuleByProto($decision->dataArray['protoId']);
                if (is_null($licenseRule) || !$licenseRule->isCompanyAllowed($decision->company)) {
                    $decision->addError('dataArray[protoId]', Yii::t('app', 'Getting this license type not allowed'));
                }
            }
        }
        
        return !count($decision->getErrors());
    }
    
    public function getDefaultData(CompanyDecision $decision)
    {
        return [
            'stateId' => $decision->company->stateId,
        ];
    }

}
