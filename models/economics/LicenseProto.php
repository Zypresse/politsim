<?php

namespace app\models\economics;

use Yii,
    app\models\base\ObjectWithFixedPrototypes;

/**
 * 
 */
class LicenseProto extends ObjectWithFixedPrototypes
{
    
    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;


    protected static function getList()
    {
        return [
            [
                'id' => LicenseProtoType::BANK,
                'name' => Yii::t('app', 'License for banking'),
            ],
        ];
    }

}
