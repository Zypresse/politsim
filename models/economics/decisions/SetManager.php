<?php

namespace app\models\economics\decisions;

use Yii,
    app\components\LinkCreator,
    app\models\economics\Utr,
    app\models\economics\units\BaseUnit,
    app\models\User,
    app\models\economics\CompanyDecision,
    app\models\economics\CompanyDecisionProto;

/**
 * 
 */
final class SetManager extends CompanyDecisionProto
{
    
    public function accept(CompanyDecision $decision): bool
    {
        $object = Utr::findByPk($decision->dataArray['utr'])->object;
        $object->managerId = $decision->dataArray['userId'];
        return $object->save();
    }

    public function render(CompanyDecision $decision): string
    {
        $user = User::findByPk($decision->dataArray['userId']);
        $object = Utr::findByPk($decision->dataArray['utr'])->object;
        return Yii::t('app', 'Set {0} as manager of {1}', [
            LinkCreator::userLink($user),
            LinkCreator::link($object),
        ]);
    }

    public function validate(CompanyDecision $decision): bool
    {
        if (!isset($decision->dataArray['utr']) || !$decision->dataArray['utr']) {
            $decision->addError('dataArray[utr]', Yii::t('app', 'Object is required field'));
        } else {
            $object = Utr::findByPk($decision->dataArray['utr'])->object;
            if (!($object instanceof BaseUnit && (int)$object->masterId === (int)$decision->company->getUtr())) {
                $decision->addError('dataArray[utr]', Yii::t('app', 'Invalid object'));
            }
        }
        if (!isset($decision->dataArray['userId']) || !$decision->dataArray['userId']) {
            $decision->addError('dataArray[userId]', Yii::t('app', 'User is required field'));
        } elseif (!User::find()->where(['id' => $decision->dataArray['userId']])->exists()) {
            $decision->addError('dataArray[userId]', Yii::t('app', 'Invalid user'));
        }
        return !count($decision->getErrors());
    }

}
