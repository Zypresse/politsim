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
    app\models\politics\constitution\articles\postsonly\DestignationType,
    app\models\politics\constitution\articles\postsonly\TermsOfOffice,
    app\models\politics\constitution\articles\postsonly\TermsOfElection;

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
        $post->constitution->setArticleByType(
            ConstitutionArticleType::DESTIGNATION_TYPE, null,
            $bill->dataArray['value'],
            isset($bill->dataArray['value2']) ? $bill->dataArray['value2'] : null,
            isset($bill->dataArray['value3']) ? MyMathHelper::implodeArrayToBitmask($bill->dataArray['value3']) : null
        );
        if (isset($bill->dataArray['toValue'])) {
            $post->constitution->setArticleByType(ConstitutionArticleType::TERMS_OF_OFFICE, null, $bill->dataArray['toValue']);
        }
        if (isset($bill->dataArray['teValue']) && isset($bill->dataArray['teValue2']) && isset($bill->dataArray['teValue3'])) {
            $post->constitution->setArticleByType(
                ConstitutionArticleType::TERMS_OF_ELECTION, null,
                $bill->dataArray['teValue'],
                $bill->dataArray['teValue2'],
                $bill->dataArray['teValue3']
            );
        }
        
        return true;
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
    
    /**
     * 
     * @param Bill $bill
     */
    public function renderFull($bill): string
    {
        $post = AgencyPost::findByPk($bill->dataArray['postId']);
        
        switch ((int) $bill->dataArray['value']) {
            case DestignationType::BY_OTHER_POST:
                $otherPost = AgencyPost::findByPk($bill->dataArray['value2']);
                return Yii::$app->controller->render('/bills/renderfull/post/destignation/by-other-post', [
                    'bill' => $bill,
                    'post' => $post,
                    'otherPost' => $otherPost,
                ]);
            case DestignationType::BY_PRECURSOR:
                return Yii::$app->controller->render('/bills/renderfull/post/destignation/by-precursor', [
                    'bill' => $bill,
                    'post' => $post,
                    'otherPost' => $otherPost,
                ]);
            case DestignationType::BY_AGENCY_ELECTION:
                $agency = Agency::findByPk($bill->dataArray['value2']);
                return Yii::$app->controller->render('/bills/renderfull/post/destignation/by-agency-election', [
                    'bill' => $bill,
                    'post' => $post,
                    'agency' => $agency,
                    'settings' => DestignationType::getSelectedSettings($bill->dataArray['value3']),
                ]);
            case DestignationType::BY_REGION_ELECTION:
                $region = Region::findByPk($bill->dataArray['value2']);
                return Yii::$app->controller->render('/bills/renderfull/post/destignation/by-region-election', [
                    'bill' => $bill,
                    'post' => $post,
                    'region' => $region,
                    'settings' => DestignationType::getSelectedSettings($bill->dataArray['value3']),
                ]);
            case DestignationType::BY_DISTRICT_ELECTION:
                $district = ElectoralDistrict::findByPk($bill->dataArray['value2']);
                return Yii::$app->controller->render('/bills/renderfull/post/destignation/by-district-election', [
                    'bill' => $bill,
                    'post' => $post,
                    'district' => $district,
                    'settings' => DestignationType::getSelectedSettings($bill->dataArray['value3']),
                ]);
            case DestignationType::BY_CITY_ELECTION:
                $city = City::findByPk($bill->dataArray['value2']);
                return Yii::$app->controller->render('/bills/renderfull/post/destignation/by-city-election', [
                    'bill' => $bill,
                    'post' => $post,
                    'city' => $city,
                    'settings' => DestignationType::getSelectedSettings($bill->dataArray['value3']),
                ]);
            case DestignationType::BY_STATE_ELECTION:
                return Yii::$app->controller->render('/bills/renderfull/post/destignation/by-state-election', [
                    'bill' => $bill,
                    'post' => $post,
                    'settings' => DestignationType::getSelectedSettings($bill->dataArray['value3']),
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
                    
                    switch ((int) $bill->dataArray['value']) {
                        case DestignationType::BY_AGENCY_ELECTION:
                        case DestignationType::BY_STATE_ELECTION:
                        case DestignationType::BY_DISTRICT_ELECTION:
                        case DestignationType::BY_REGION_ELECTION:
                        case DestignationType::BY_CITY_ELECTION:
                            if (!isset($bill->dataArray['toValue']) || !$bill->dataArray['toValue']) {
                                $bill->addError('dataArray[toValue]', Yii::t('app/bills', 'Terms of office is required field'));
                            } else {
                                /* @var $article TermsOfOffice */
                                $article = $post->constitution->getArticleByType(ConstitutionArticleType::TERMS_OF_OFFICE);
                                $article->value = $bill->dataArray['toValue'];
                                if (!$article->validate(['value'])) {
                                    foreach ($article->getErrors() as $attr => $errors) {
                                        foreach ($errors as $error) {
                                            $bill->addError('dataArray[toValue]', $error);
                                        }
                                    }
                                }
                            }
                            
                            $teInputed = true;
                            if (!isset($bill->dataArray['teValue'])) {
                                $bill->addError('dataArray[teValue]', Yii::t('app/bills', 'Registration time is required field'));
                                $teInputed = false;
                            }
                            if (!isset($bill->dataArray['teValue2'])) {
                                $bill->addError('dataArray[teValue2]', Yii::t('app/bills', 'Election pause is required field'));
                                $teInputed = false;
                            }
                            if (!isset($bill->dataArray['teValue3'])) {
                                $bill->addError('dataArray[teValue3]', Yii::t('app/bills', 'Voting time is required field'));
                                $teInputed = false;
                            }
                            
                            if ($teInputed) {
                                /* @var $article TermsOfElection */
                                $article = $post->constitution->getArticleByType(ConstitutionArticleType::TERMS_OF_ELECTION);
                                $article->value = $bill->dataArray['teValue'];
                                $article->value2 = $bill->dataArray['teValue2'];
                                $article->value3 = $bill->dataArray['teValue3'];
                                if (!$article->validate(['value', 'value2', 'value3'])) {
                                    foreach ($article->getErrors() as $attr => $errors) {
                                        foreach ($errors as $error) {
                                            $bill->addError('dataArray[te'.str_replace('value', 'Value', $attr).']', $error);
                                        }
                                    }
                                }
                            }
                            break;
                    }
                }
            }
        }
        
        return !!count($bill->getErrors());
    }

}
