<?php

namespace app\models\politics\bills\prototypes;

use Yii,
    app\models\politics\bills\BillProtoInterface,
    app\models\politics\bills\Bill,
    app\components\LinkCreator,
    app\models\politics\Agency,
    yii\helpers\Html;
        
/**
 * Переименование агенства
 */
class RenameAgency implements BillProtoInterface
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $agency = Agency::findByPk($bill->dataArray['agencyId']);
        $agency->name = $bill->dataArray['name'];
        $agency->nameShort = $bill->dataArray['nameShort'];
        return $agency->save();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $agency = Agency::findByPk($bill->dataArray['agencyId']);
        return Yii::t('app/bills', 'Rename agency {0} to «{1}» ({2})', [
            LinkCreator::agencyLink($agency),
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
        if (!isset($bill->dataArray['agencyId']) || !$bill->dataArray['agencyId']) {
            $bill->addError('dataArray[agencyId]', Yii::t('app/bills', 'Agency is required field'));
        } else {
            $agency = Agency::findByPk($bill->dataArray['agencyId']);
            if (is_null($agency) || $agency->stateId != $bill->stateId) {
                $bill->addError('dataArray[agencyId]', Yii::t('app/bills', 'Invalid agency'));
            }
        }
        if (!isset($bill->dataArray['name']) || !$bill->dataArray['name']) {
            $bill->addError('dataArray[name]', Yii::t('app/bills', 'Agency name is required field'));
        }
        if (!isset($bill->dataArray['nameShort']) || !$bill->dataArray['nameShort']) {
            $bill->addError('dataArray[nameShort]', Yii::t('app/bills', 'Agency short name is required field'));
        }
        return !!count($bill->getErrors());
        
    }

}