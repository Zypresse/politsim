<?php

namespace app\models\politics\bills\prototypes;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\AgencyTemplate;

/**
 * Создать агенство по шаблону
 */
final class CreateAgency extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $template = AgencyTemplate::findOne($bill->dataArray['agencyTemplateId']);
        $agency = $template->create($bill->stateId);
        return $agency->id > 0;
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $template = AgencyTemplate::findOne($bill->dataArray['agencyTemplateId']);
        return Yii::t('app/bills', 'Create new organisation by template «{0}»', [
            $template->name
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
        return !!count($bill->getErrors());
    }
}
