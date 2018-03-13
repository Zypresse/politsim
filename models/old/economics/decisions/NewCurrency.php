<?php

namespace app\models\economics\decisions;

use Yii,
    yii\helpers\Html,
    app\models\economics\Company,
    app\models\economics\CompanyDecision,
    app\models\economics\CompanyDecisionProto,
    app\models\economics\resources\Currency,
    app\models\economics\LicenseProtoType;

/**
 * 
 */
final class NewCurrency extends CompanyDecisionProto
{
    
    public function accept(CompanyDecision $decision): bool
    {
        $currency = new Currency([
            'emissionerId' => $decision->company->getUtr(),
        ]);
        $currency->load($decision->dataArray, '');
        return $currency->save();
    }

    public function render(CompanyDecision $decision): string
    {
        return Yii::t('app', 'Emissionate new currency «{0}» ({1}) with exchange rate {2}', [
            Html::encode($decision->dataArray['name']),
            Html::encode($decision->dataArray['nameShort']),
            $decision->dataArray['exchangeRate'],
        ]);
    }

    public function validate(CompanyDecision $decision): bool
    {
        $currency = new Currency([
            'emissionerId' => $decision->company->getUtr(),
        ]);
        $currency->load($decision->dataArray, '');
        $currency->validate();
        foreach ($currency->getErrors() as $attr => $errors) {
            foreach ($errors as $error) {
                $decision->addError('dataArray['.$attr.']', $error);
            }
        }
        
        return !count($decision->getErrors());
    }
    
    public static function isAvailable(Company $company) : bool
    {
        return $company->isHaveLicense(LicenseProtoType::CURRENCY_EMISSION, $company->stateId);
    }
    
    public function getDefaultData(CompanyDecision $decision)
    {
        return [
            'exchangeRate' => 1,
        ];
    }

}
