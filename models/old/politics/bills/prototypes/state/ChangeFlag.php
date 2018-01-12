<?php

namespace app\models\politics\bills\prototypes\state;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\components\LinkHelper,
    yii\helpers\Html;

/**
 * Смена флага государства
 */
class ChangeFlag extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill) : bool
    {
        $bill->state->flag = $bill->dataArray['flag'];
        return $bill->state->save();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill) : bool
    {
        if (!isset($bill->dataArray['flag']) || !$bill->dataArray['flag']) {
            $bill->addError('dataArray[flag]', Yii::t('app/bills', 'Flag is required field'));
        } else if (!LinkHelper::isImageLink($bill->dataArray['flag'])) {
            $bill->addError('dataArray[flag]', Yii::t('app/bills', 'Flag must be valid link to image'));
        }
        return !count($bill->getErrors());
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        return Yii::t('app/bills', 'Change our state flag to {0}', [
            Html::img($bill->dataArray['flag'], ['style' => 'height: 16px;']),
        ]);
    }

}
