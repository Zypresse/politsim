<?php

namespace app\models\politics\bills\prototypes;

use Yii,
    app\models\politics\bills\BillProtoInterface,
    app\models\politics\bills\Bill,
    app\models\politics\State;

/**
 * Переименование государства
 */
class RenameState implements BillProtoInterface
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill) : bool
    {
        $data = json_decode($bill->data);
        $bill->state->name = $data->name;
        $bill->state->nameShort = $data->nameShort;
        $bill->state->save();
        return true;
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill) : bool
    {
        if (!$bill->dataArray['name']) {
            $bill->addError('dataArray[name]', Yii::t('app', 'State name is required field'));
        }
        if (!$bill->dataArray['nameShort']) {
            $bill->addError('dataArray[nameShort]', Yii::t('app', 'State short name is required field'));
        }
        return !!count($bill->getErrors());
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        return Yii::t('app/bills', 'Rename our state to «{0}» ({1})', [
            $bill->dataArray['name'],
            $bill->dataArray['nameShort'],
        ]);
    }

}
