<?php

namespace app\models\politics\bills\prototypes\state;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    yii\helpers\Html;

/**
 * Переименование государства
 */
class Rename extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill) : bool
    {
        $bill->state->name = $bill->dataArray['name'];
        $bill->state->nameShort = $bill->dataArray['nameShort'];
        return $bill->state->save();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill) : bool
    {
        if (!isset($bill->dataArray['name']) || !$bill->dataArray['name']) {
            $bill->addError('dataArray[name]', Yii::t('app/bills', 'State name is required field'));
        }
        if (!isset($bill->dataArray['nameShort']) || !$bill->dataArray['nameShort']) {
            $bill->addError('dataArray[nameShort]', Yii::t('app/bills', 'State short name is required field'));
        }
        
        if (!count($bill->getErrors())) {
            $bill->state->name = $bill->dataArray['name'];
            $bill->state->nameShort = $bill->dataArray['nameShort'];
            if (!$bill->state->validate()) {
                foreach ($bill->state->getErrors() as $attr => $errors) {
                    foreach ($errors as $error) {
                        $bill->addError("dataArray[{$attr}]", $error);
                    }
                }
            }
        }
        
        return !count($bill->getErrors());
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        return Yii::t('app/bills', 'Rename our state to «{0}» ({1})', [
            Html::encode($bill->dataArray['name']),
            Html::encode($bill->dataArray['nameShort']),
        ]);
    }

}
