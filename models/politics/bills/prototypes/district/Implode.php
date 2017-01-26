<?php

namespace app\models\politics\bills\prototypes\district;

use Yii,
    yii\helpers\Html,
    app\components\TileCombiner,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\elections\ElectoralDistrict,
    app\models\politics\constitution\ConstitutionArticle,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\DestignationType,
    app\models\Tile;
        
/**
 * Включить второй округ в состав первого
 */
class Implode extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $district1 = ElectoralDistrict::findByPk($bill->dataArray['district1Id']);
        $district2 = ElectoralDistrict::findByPk($bill->dataArray['district2Id']);
        
        Tile::updateAll(['electoralDistrictId' => $district1->id], ['electoralDistrictId' => $district2->id]);
        $district2->delete();
        $district1->updateParams();
        return true;
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $district1 = ElectoralDistrict::findByPk($bill->dataArray['district1Id']);
        $district2 = ElectoralDistrict::findByPk($bill->dataArray['district2Id']);
        return Yii::t('app/bills', 'Include electoral district «{0}» to electoral district «{1}»', [
            Html::encode($district2->name),
            Html::encode($district1->name),
        ]);
    }
    
    /**
     * 
     * @param Bill $bill
     */
    public function renderFull($bill) : string
    {
        $district1 = ElectoralDistrict::findByPk($bill->dataArray['district1Id']);
        $district2 = ElectoralDistrict::findByPk($bill->dataArray['district2Id']);
                
        return Yii::$app->controller->render('/bills/renderfull/district/implode', [
            'district1' => $district1,
            'district2' => $district2,
        ]);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        if (!isset($bill->dataArray['district1Id']) || !$bill->dataArray['district1Id']) {
            $bill->addError('dataArray[district1Id]', Yii::t('app/bills', 'Main electoral district is required field'));
        } else {
            $district1 = ElectoralDistrict::findByPk($bill->dataArray['district1Id']);
            if (is_null($district1) || $district1->stateId != $bill->stateId) {
                $bill->addError('dataArray[district1Id]', Yii::t('app/bills', 'Invalid electoral district'));
            }
        }
        if (!isset($bill->dataArray['district2Id']) || !$bill->dataArray['district2Id']) {
            $bill->addError('dataArray[district2Id]', Yii::t('app/bills', 'Children electoral district is required field'));
        } else {
            $district2 = ElectoralDistrict::findByPk($bill->dataArray['district2Id']);
            if (is_null($district2) || $district2->stateId != $bill->stateId) {
                $bill->addError('dataArray[district2Id]', Yii::t('app/bills', 'Invalid electoral district'));
            } else {
                if ( ConstitutionArticle::find()
                        ->where([
                            'type' => ConstitutionArticleType::DESTIGNATION_TYPE,
                            'value' => DestignationType::BY_DISTRICT_ELECTION,
                            'value2' => $district2->id
                        ])
                        ->exists()) {
                    $bill->addError('dataArray[district2Id]', Yii::t('app/bills', 'This electoral district used by elections to posts'));
                }
            }
        }
        
        if (isset($bill->dataArray['district1Id']) && isset($bill->dataArray['district2Id'])) {
            if ((int)$bill->dataArray['district1Id'] === (int)$bill->dataArray['district2Id']) {
                $bill->addError('dataArray[district2Id]', Yii::t('app/bills', 'Select some other electoral district'));
            }
        }
        return !count($bill->getErrors());
        
    }

}
