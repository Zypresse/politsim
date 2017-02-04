<?php

namespace app\models\politics\bills\prototypes\company;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\components\LinkCreator,
    app\models\economics\Company,
    app\models\economics\Utr,
    yii\helpers\Html;

/**
 * 
 */
final class SetShareholder extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $company = Company::findByPk($bill->dataArray['companyId']);
        
        foreach ($company->getShares()->with('masterUtr')->all() as $share) {
            if ($share->master->isGoverment($bill->stateId)) {
                $share->masterId = $bill->dataArray['shareholderUtr'];
                $share->locationId = $bill->dataArray['shareholderUtr'];
                $share->save();
            }
        }
        return true;
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $company = Company::findByPk($bill->dataArray['companyId']);
        $utrModel = Utr::findByPk($bill->dataArray['shareholderUtr']);
        return Yii::t('app/bills', 'Set {1} as shareholder of company {0}', [
            LinkCreator::companyLink($company),
            LinkCreator::link($utrModel->object),
        ]);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        
        if (!isset($bill->dataArray['companyId']) || !$bill->dataArray['companyId']) {
            $bill->addError('dataArray[companyId]', Yii::t('app/bills', 'Company is required field'));
        } else {
            $company = Company::findByPk($bill->dataArray['companyId']);
            if (is_null($company) || $company->stateId != $bill->stateId || !($company->isGoverment || $company->isHalfGoverment)) {
                $bill->addError('dataArray[shareholderUtr]', Yii::t('app/bills', 'Invalid company'));
            }
        }
        
        if (!isset($bill->dataArray['shareholderUtr']) || !$bill->dataArray['shareholderUtr']) {
            $bill->addError('dataArray[shareholderUtr]', Yii::t('app/bills', 'Shareholder is required field'));
        } else {
            $utrModel = Utr::findByPk($bill->dataArray['shareholderUtr']);
            if (is_null($utrModel) || is_null($utrModel->object) || !$utrModel->object->isGoverment($bill->stateId)) {
                $bill->addError('dataArray[shareholderUtr]', Yii::t('app/bills', 'Invalid shareholder'));
            }
        }
        
        return !count($bill->getErrors());
        
    }

}
