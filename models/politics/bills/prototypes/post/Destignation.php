<?php

namespace app\models\politics\bills\prototypes\post;

use Yii,
    yii\helpers\Html,
    app\components\LinkCreator,
    app\components\MyMathHelper,
    app\models\politics\AgencyPost,
    app\models\politics\Agency,
    app\models\politics\City,
    app\models\politics\Region,
    app\models\politics\elections\ElectoralDistrict,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\DestignationType;

/**
 * 
 */
final class Destignation extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $post = AgencyPost::findByPk($bill->dataArray['postId']);
        return $post->constitution->setArticleByType(
                ConstitutionArticleType::DESTIGNATION_TYPE, null,
                $bill->dataArray['value'],
                isset($bill->dataArray['value2']) ? $bill->dataArray['value2'] : null,
                isset($bill->dataArray['value3']) ? MyMathHelper::implodeArrayToBitmask($bill->dataArray['value3']) : null
            );
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $post = AgencyPost::findByPk($bill->dataArray['postId']); 
        return Yii::t('app/bills', 'Change agency post «{0}» destignation type to {1}', [
            Html::encode($post->name),
            DestignationType::getNameStatic($bill->dataArray['value']),
        ]);
    }

    private function getSelected(int $value)
    {
        $selected = [];
        foreach (DestignationType::getList() as $val => $name) {
            if ($value & $val) {
                $selected[$val] = $name;
            }
        }
        return $selected;
    }
    
    /**
     * 
     * @param Bill $bill
     */
    public function renderFull($bill): string
    {
        $post = AgencyPost::findByPk($bill->dataArray['postId']); // TODO кем выбирается и настройки выборов
        
        switch ((int) $bill->dataArray['value']) {
            case DestignationType::BY_OTHER_POST:
                $otherPost = AgencyPost::findByPk($bill->dataArray['value2']);
                return Yii::t('app/bills', 'Change agency post «{0}» destignation type to destignation by agency post «{1}»', [
                    Html::encode($post->name),
                    Html::encode($otherPost->name),
                ]);
            case DestignationType::BY_PRECURSOR:
                return Yii::t('app/bills', 'Change agency post «{0}» destignation type to destignation by precursor', [
                    Html::encode($post->name),
                ]);
            case DestignationType::BY_AGENCY_ELECTION:
                $agency = Agency::findByPk($bill->dataArray['value2']);
                return Yii::t('app/bills', 'Change agency post «{0}» destignation type to destignation by elections in agency {1} with next settings: {2}', [
                    Html::encode($post->name),
                    LinkCreator::agencyLink($agency),
                    implode(',', $this->getSelected($bill->dataArray['value3'])),
                ]);
            case DestignationType::BY_REGION_ELECTION:
                $region = Region::findByPk($bill->dataArray['value2']);
                return Yii::t('app/bills', 'Change agency post «{0}» destignation type to destignation by elections in region {1} with next settings: {2}', [
                    Html::encode($post->name),
                    LinkCreator::regionLink($region),
                    implode(',', $this->getSelected($bill->dataArray['value3'])),
                ]);
            case DestignationType::BY_DISTRICT_ELECTION:
                $district = ElectoralDistrict::findByPk($bill->dataArray['value2']);
                return Yii::t('app/bills', 'Change agency post «{0}» destignation type to destignation by elections in electoral district «{1}» with next settings: {2}', [
                    Html::encode($post->name),
                    Html::encode($district->name),
                    implode(',', $this->getSelected($bill->dataArray['value3'])),
                ]);
            case DestignationType::BY_CITY_ELECTION:
                $city = City::findByPk($bill->dataArray['value2']);
                return Yii::t('app/bills', 'Change agency post «{0}» destignation type to destignation by elections in city {1} with next settings: {2}', [
                    Html::encode($post->name),
                    LinkCreator::cityLink($city),
                    implode(',', $this->getSelected($bill->dataArray['value3'])),
                ]);
            case DestignationType::BY_STATE_ELECTION:
                return Yii::t('app/bills', 'Change agency post «{0}» destignation type to destignation by state elections with next settings: {2}', [
                    Html::encode($post->name),
                    implode(',', $this->getSelected($bill->dataArray['value3'])),
                ]);
        }
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        
        if (!isset($bill->dataArray['postId']) || !$bill->dataArray['postId']) {
            $bill->addError('dataArray[postId]', Yii::t('app/bills', 'Agency post is required field'));
        } else {
            $post = AgencyPost::findByPk($bill->dataArray['postId']);
            if (is_null($post) || $post->stateId != $bill->stateId) {
                $bill->addError('dataArray[postId]', Yii::t('app/bills', 'Invalid agency post'));
            } else {
                if (!isset($bill->dataArray['value']) || !$bill->dataArray['value']) {
                    $bill->addError('dataArray[value]', Yii::t('app/bills', 'Destignation type is required field'));
                } else {
                    /* @var $article DestignationType */
                    $article = $post->constitution->getArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE);
                    $article->value = $bill->dataArray['value'];
                    $article->value2 = isset($bill->dataArray['value2']) ? $bill->dataArray['value2'] : null;
                    $article->value3 = isset($bill->dataArray['value2']) ? MyMathHelper::implodeArrayToBitmask($bill->dataArray['value3']) : null;
                    if (!$article->validate(['value', 'value2', 'value3'])) {
                        foreach ($article->getErrors() as $attr => $errors) {
                            foreach ($errors as $error) {
                                $bill->addError('dataArray['.$attr.']', $error);
                            }
                        }
                    }
                }
            }
        }
        
        return !!count($bill->getErrors());
    }

}
