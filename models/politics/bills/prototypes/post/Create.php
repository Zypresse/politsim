<?php

namespace app\models\politics\bills\prototypes\post;

use Yii,
    yii\helpers\Html,
    app\components\LinkCreator,
    app\components\MyMathHelper,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\ConstitutionOwnerType,
    app\models\politics\constitution\articles\postsonly\DestignationType,
    app\models\politics\constitution\articles\postsonly\TermsOfElection,
    app\models\politics\constitution\articles\postsonly\TermsOfOffice,
    app\models\politics\Agency,
    app\models\politics\AgencyPost;

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
        $post = new AgencyPost([
            'stateId' => $bill->stateId,
            'name' => $bill->dataArray['name'],
            'nameShort' => $bill->dataArray['nameShort'],
        ]);
        $post->save();

        $agency = Agency::findByPk($bill->dataArray['agencyId']);
        $post->link('agencies', $agency);
        
        $post->constitution->setArticleByType(
            ConstitutionArticleType::DESTIGNATION_TYPE, null,
            $bill->dataArray['destignationValue'],
            isset($bill->dataArray['destignationValue2']) ? $bill->dataArray['destignationValue2'] : null,
            isset($bill->dataArray['destignationValue3']) ? MyMathHelper::implodeArrayToBitmask($bill->dataArray['destignationValue3']) : null
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
        $agency = Agency::findByPk($bill->dataArray['agencyId']);
        return Yii::t('app/bills', 'Create new agency post «{0}» ({1}) in agency {2}', [
            Html::encode($bill->dataArray['name']),
            Html::encode($bill->dataArray['nameShort']),
            LinkCreator::agencyLink($agency),
        ]);
    }
    
    /**
     * 
     * @param Bill $bill
     */
    public function renderFull($bill): string
    {
        $agency = Agency::findByPk($bill->dataArray['agencyId']);
        switch ((int) $bill->dataArray['destignationValue']) {
            case DestignationType::BY_OTHER_POST:
                $otherPost = AgencyPost::findByPk($bill->dataArray['destignationValue2']);
                return Yii::$app->controller->render('/bills/renderfull/post/create/by-other-post', [
                    'bill' => $bill,
                    'agency' => $agency,
                    'otherPost' => $otherPost,
                ]);
            case DestignationType::BY_PRECURSOR:
                return Yii::$app->controller->render('/bills/renderfull/post/create/by-precursor', [
                    'bill' => $bill,
                    'agency' => $agency,
                    'otherPost' => $otherPost,
                ]);
            case DestignationType::BY_AGENCY_ELECTION:
                $agencyDestignator = Agency::findByPk($bill->dataArray['destignationValue2']);
                return Yii::$app->controller->render('/bills/renderfull/post/create/by-agency-election', [
                    'bill' => $bill,
                    'agency' => $agency,
                    'agencyDestignator' => $agencyDestignator,
                    'settings' => DestignationType::getSelectedSettings($bill->dataArray['destignationValue3']),
                ]);
            case DestignationType::BY_REGION_ELECTION:
                $region = Region::findByPk($bill->dataArray['destignationValue2']);
                return Yii::$app->controller->render('/bills/renderfull/post/create/by-region-election', [
                    'bill' => $bill,
                    'agency' => $agency,
                    'region' => $region,
                    'settings' => DestignationType::getSelectedSettings($bill->dataArray['destignationValue3']),
                ]);
            case DestignationType::BY_DISTRICT_ELECTION:
                $district = ElectoralDistrict::findByPk($bill->dataArray['destignationValue2']);
                return Yii::$app->controller->render('/bills/renderfull/post/create/by-district-election', [
                    'bill' => $bill,
                    'agency' => $agency,
                    'district' => $district,
                    'settings' => DestignationType::getSelectedSettings($bill->dataArray['destignationValue3']),
                ]);
            case DestignationType::BY_CITY_ELECTION:
                $city = City::findByPk($bill->dataArray['destignationValue2']);
                return Yii::$app->controller->render('/bills/renderfull/post/create/by-city-election', [
                    'bill' => $bill,
                    'agency' => $agency,
                    'city' => $city,
                    'settings' => DestignationType::getSelectedSettings($bill->dataArray['destignationValue3']),
                ]);
            case DestignationType::BY_STATE_ELECTION:
                return Yii::$app->controller->render('/bills/renderfull/post/create/by-state-election', [
                    'bill' => $bill,
                    'agency' => $agency,
                    'settings' => DestignationType::getSelectedSettings($bill->dataArray['destignationValue3']),
                ]);
        }
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        if (!isset($bill->dataArray['name']) || !$bill->dataArray['name']) {
            $bill->addError('dataArray[name]', Yii::t('app/bills', 'Agency post name is required field'));
        }
        if (!isset($bill->dataArray['nameShort']) || !$bill->dataArray['nameShort']) {
            $bill->addError('dataArray[nameShort]', Yii::t('app/bills', 'Agency post short name is required field'));
        }
        if (!isset($bill->dataArray['agencyId']) || !$bill->dataArray['agencyId']) {
            $bill->addError('dataArray[agencyId]', Yii::t('app/bills', 'Agency is required field'));
        } else {
            $agency = Agency::findByPk($bill->dataArray['agencyId']);
            if (is_null($agency) || $agency->stateId != $bill->stateId) {
                $bill->addError('dataArray[agencyId]', Yii::t('app/bills', 'Invalid agency'));
            }
        }
        if (!isset($bill->dataArray['destignationValue']) || !$bill->dataArray['destignationValue']) {
            $bill->addError('dataArray[destignationValue]', Yii::t('app/bills', 'Destignation type is required field'));
        } else {
            $article = new DestignationType([
                'ownerId' => $bill->postId,
                'ownerType' => ConstitutionOwnerType::POST,
                'type' => ConstitutionArticleType::DESTIGNATION_TYPE,
            ]);
            $article->value = $bill->dataArray['destignationValue'];
            $article->value2 = isset($bill->dataArray['destignationValue2']) ? $bill->dataArray['destignationValue2'] : null;
            $article->value3 = isset($bill->dataArray['destignationValue3']) ? MyMathHelper::implodeArrayToBitmask($bill->dataArray['destignationValue3']) : null;
            if (!$article->validate(['value', 'value2', 'value3'])) {
                foreach ($article->getErrors() as $attr => $errors) {
                    foreach ($errors as $error) {
                        $bill->addError('dataArray[destignation'.str_replace('value', 'Value', $attr).']', $error);
                    }
                }
            }
            
            switch ((int) $bill->dataArray['destignationValue']) {
                case DestignationType::BY_AGENCY_ELECTION:
                case DestignationType::BY_STATE_ELECTION:
                case DestignationType::BY_DISTRICT_ELECTION:
                case DestignationType::BY_REGION_ELECTION:
                case DestignationType::BY_CITY_ELECTION:
                    if (!isset($bill->dataArray['toValue']) || !$bill->dataArray['toValue']) {
                        $bill->addError('dataArray[toValue]', Yii::t('app/bills', 'Terms of office is required field'));
                    } else {
                        $article = new TermsOfOffice([
                            'ownerId' => $bill->postId,
                            'ownerType' => ConstitutionOwnerType::POST,
                            'type' => ConstitutionArticleType::TERMS_OF_OFFICE,
                        ]);
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
                        $article = new TermsOfElection([
                            'ownerId' => $bill->postId,
                            'ownerType' => ConstitutionOwnerType::POST,
                            'type' => ConstitutionArticleType::TERMS_OF_ELECTION,
                        ]);
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
        
        return !!count($bill->getErrors());
    }

}
