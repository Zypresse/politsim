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

}
