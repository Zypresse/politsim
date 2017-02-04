<?php

namespace app\models\economics\resources\base;

use yii\base\Model,
    app\components\MyHtmlHelper,
    app\models\economics\ResourceProtoInterface;

/**
 * 
 */
abstract class NoSubtypesResourceProto extends Model implements ResourceProtoInterface
{
    
    /**
     * ID субпрототипа
     * @var integer
     */
    public $id;
    
    public static function loadSubtype($subId = null)
    {
        return new static([
            'id' => $subId,
        ]);
    }
    
    public function getIconImage()
    {
        return null;
    }
    
    public function getIcon()
    {
        return MyHtmlHelper::customIcon($this->getIconImage(), $this->getName(), 'width: 16px; height: 16px;');
    }
    
}
