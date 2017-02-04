<?php

namespace app\models\politics\bills\prototypes\company;

use Yii,
    app\components\MyHtmlHelper,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\economics\LicenseProto,
    app\models\politics\LicenseRule as LicenseRuleModel;

/**
 * 
 */
final class LicenseRule extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $model = LicenseRuleModel::findOrCreate([
            'stateId' => $bill->stateId,
            'protoId' => $bill->dataArray['protoId'],
        ], true, [
            'whichCompaniesAllowed' => $bill->dataArray['whichCompaniesAllowed'],
            'isNeedConfirmation' => $bill->dataArray['isNeedConfirmation'],
            'priceForResidents' => $bill->dataArray['priceForResidents'],
            'priceForNonresidents' => $bill->dataArray['priceForNonresidents'],
        ], false);
        return $model->save();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $proto = LicenseProto::findOne($bill->dataArray['protoId']);
        return Yii::t('app/bills', 'Change rule for license «{0}»', [$proto->name]);
    }
    
    /**
     * 
     * @param Bill $bill
     */
    public function renderFull($bill): string
    {
        $proto = LicenseProto::findOne($bill->dataArray['protoId']);
        return Yii::t('app/bills', 'Change rule for license «{0}»', [$proto->name]).'<br>'.
                Yii::t('app/bills', '<strong>Which companies allowed:</strong> {0}', [LicenseRuleModel::getWhichAllowedName($bill->dataArray['whichCompaniesAllowed'])]).'<br>'.
                Yii::t('app/bills', '<strong>Is need goverment confirmation:</strong> {0}', [Yii::t('yii', $bill->dataArray['isNeedConfirmation'] ? 'Yes' : 'No')]).'<br>'.
                Yii::t('app/bills', '<strong>Price for residents:</strong> {0}', [MyHtmlHelper::moneyFormat($bill->dataArray['priceForResidents'])]).'<br>'.
                Yii::t('app/bills', '<strong>Price for nonresidents:</strong> {0}', [MyHtmlHelper::moneyFormat($bill->dataArray['priceForNonresidents'])]);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        
        if (!isset($bill->dataArray['protoId']) || !$bill->dataArray['protoId']) {
            $bill->addError('dataArray[protoId]', Yii::t('app/bills', 'License type is required field'));
        } elseif (!LicenseProto::exist($bill->dataArray['protoId'])) {
            $bill->addError('dataArray[protoId]', Yii::t('app/bills', 'Invalid license type'));
        }
        if (!isset($bill->dataArray['whichCompaniesAllowed']) || !$bill->dataArray['whichCompaniesAllowed']) {
            $bill->addError('dataArray[whichCompaniesAllowed]', Yii::t('app/bills', 'Which companies allowed is required field'));
        }
        if (!isset($bill->dataArray['isNeedConfirmation'])) {
            $bill->addError('dataArray[isNeedConfirmation]', Yii::t('app/bills', 'Is need confirmation is required field'));
        }
        if (!isset($bill->dataArray['priceForResidents']) || $bill->dataArray['priceForResidents'] < 0) {
            $bill->addError('dataArray[priceForResidents]', Yii::t('app/bills', 'Price for residents is required field'));
        }
        if (!isset($bill->dataArray['priceForNonresidents']) || $bill->dataArray['priceForNonresidents'] < 0) {
            $bill->addError('dataArray[priceForNonresidents]', Yii::t('app/bills', 'Price for nonresidents is required field'));
        }
        
        return !count($bill->getErrors());
    }
    
    /**
     * 
     * @param Bill $bill
     */
    public function getDefaultData($bill)
    {
        return [
            'priceForResidents' => 0,
            'priceForNonresidents' => 0,
        ];
    }

}
