<?php

namespace app\models\politics\bills\prototypes\company;


use Yii,
    yii\helpers\Html,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\economics\Company,
    app\models\economics\Resource,
    app\models\economics\ResourceProto,
    app\models\politics\State;

/**
 * 
 */
final class Create extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $company = $this->instantiateCompany($bill);
        $company->save();
        
        $shares = new Resource([
            'protoId' => ResourceProto::SHARE,
            'subProtoId' => $company->id,
            'masterId' => $bill->state->getUtr(),
            'locationId' => $bill->state->getUtr(),
            'count' => $company->sharesIssued,
        ]);
        $shares->save();
        
        $company->updateParams();
        return true;
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        return Yii::t('app/bills', 'Create new goverment company {2} «{0}» ({1})', [
            Html::encode($bill->dataArray['name']),
            Html::encode($bill->dataArray['nameShort']),
            isset($bill->dataArray['flag']) && $bill->dataArray['flag'] ? Html::img($bill->dataArray['flag'], ['style' => 'height:1em;']) : '',
        ]);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        $company = $this->instantiateCompany($bill);
        if (!$company->validate()) {
            foreach ($company->getErrors() as $attr => $errors) {
                foreach ($errors as $error) {
                    $bill->addError('dataArray['.$attr.']', $error);
                }
            }
        }
        return !count($bill->getErrors());
    }
        
    /**
     * 
     * @param Bill $bill
     * @return Company
     */
    private function instantiateCompany(Bill $bill)
    {
        $company = new Company([
            'stateId' => $bill->stateId,
        ]);
        $company->load($bill->dataArray, '');
        return $company;
    }

}
