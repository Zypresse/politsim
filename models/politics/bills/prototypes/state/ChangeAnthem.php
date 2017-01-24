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
class ChangeAnthem extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill) : bool
    {
        $bill->state->anthem = $bill->dataArray['anthem'];
        return $bill->state->save();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill) : bool
    {
        if (!isset($bill->dataArray['anthem']) || !$bill->dataArray['anthem']) {
            $bill->addError('dataArray[anthem]', Yii::t('app/bills', 'Anthem is required field'));
        } else if (!LinkHelper::isSoundCloudLink($bill->dataArray['anthem'])) {
            $bill->addError('dataArray[anthem]', Yii::t('app/bills', 'Anthem must be valid SoundCloud link'));
        }
        return !!count($bill->getErrors());
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        return Yii::t('app/bills', 'Change our state anthem to {0}', [
            Html::a($bill->dataArray['anthem'], $bill->dataArray['anthem']),
        ]);
    }

}
