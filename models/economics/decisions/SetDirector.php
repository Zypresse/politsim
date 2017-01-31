<?php

namespace app\models\economics\decisions;

use Yii,
    app\components\LinkCreator,
    app\models\User,
    app\models\economics\CompanyDecision,
    app\models\economics\CompanyDecisionProto;

/**
 * 
 */
final class SetDirector extends CompanyDecisionProto
{
    
    public function accept(CompanyDecision $decision): bool
    {
        $decision->company->directorId = $decision->dataArray['userId'];
        return $decision->company->save();
    }

    public function render(CompanyDecision $decision): string
    {
        $user = User::findByPk($decision->dataArray['userId']);
        return Yii::t('app', 'Set {0} as director', [
            LinkCreator::userLink($user),
        ]);
    }

    public function validate(CompanyDecision $decision): bool
    {
        if (is_null($decision->dataArray['userId']) || !$decision->dataArray['userId']) {
            $decision->addError('dataArray[userId]', Yii::t('app', 'User is required field'));
        } elseif (!User::find()->where(['id' => $decision->dataArray['userId']])->exists()) {
            $decision->addError('dataArray[userId]', Yii::t('app', 'Invalid user'));
        }
        return !count($decision->getErrors());
    }

}
