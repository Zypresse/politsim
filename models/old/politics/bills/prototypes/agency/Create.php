<?php

namespace app\models\politics\bills\prototypes\agency;

use Yii,
    yii\helpers\Html,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\AgencyTemplate,
    app\models\politics\Agency;

/**
 * Создать агенство по шаблону
 */
final class Create extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $template = AgencyTemplate::findOne($bill->dataArray['agencyTemplateId']);
        $agency = $template->create($bill->stateId, $bill->dataArray);
        return $agency->id > 0;
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $template = AgencyTemplate::findOne($bill->dataArray['agencyTemplateId']);
        return Yii::t('app/bills', 'Create new organisation «{1}» ({2}) by template «{0}»', [
            $template->name,
            Html::encode($bill->dataArray['name']),
            Html::encode($bill->dataArray['nameShort']),
        ]);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        if (!isset($bill->dataArray['agencyTemplateId']) || !$bill->dataArray['agencyTemplateId'] || !AgencyTemplate::exist($bill->dataArray['agencyTemplateId'])) {
            $bill->addError('dataArray[agencyTemplateId]', Yii::t('app/bills', 'Agency template is required field'));
        }
        if (!isset($bill->dataArray['name']) || !$bill->dataArray['name']) {
            $bill->addError('dataArray[name]', Yii::t('app/bills', 'Agency name is required field'));
        }
        if (!isset($bill->dataArray['nameShort']) || !$bill->dataArray['nameShort']) {
            $bill->addError('dataArray[nameShort]', Yii::t('app/bills', 'Agency short name is required field'));
        }
        if (!count($bill->getErrors())) {
            $agency = new Agency([
                'stateId' => $bill->stateId,
                'name' => $bill->dataArray['name'],
                'nameShort' => $bill->dataArray['nameShort'],
            ]);
            if (!$agency->validate()) {
                foreach ($agency->getErrors() as $attr => $errors) {
                    foreach ($errors as $error) {
                        $bill->addError("dataArray[{$attr}]", $error);
                    }
                }
            }
        }
        return !count($bill->getErrors());
    }
}
