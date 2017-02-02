<?php

namespace app\models\economics\resources;

use Yii,
    app\components\LinkCreator,
    app\models\economics\resources\base\NoSubtypesResourceProto,
    app\models\economics\Company;

/**
 * Акция
 * 
 * @property Company $company
 * 
 */
final class Share extends NoSubtypesResourceProto
{
    
    public function getCompany()
    {
        return Company::findByPk($this->id);
    }

    public function getName()
    {
        return Yii::t('app', 'Share of {0}', [LinkCreator::companyLink($this->company)]);
    }

    public function getIconImage()
    {
        return '/img/resources/Share.png';
    }

}
