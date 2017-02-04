<?php

namespace app\models\economics;

/**
 * 
 */
final class ResourcePack
{

    /**
     *
     * @var ResourceProtoInterface
     */
    public $proto;
    
    /**
     *
     * @var double
     */
    public $count;
    
    public function __construct(float $count, int $protoId, int $subId = null)
    {
        $this->proto = ResourceProto::getPrototype($protoId, $subId);
        $this->count = $count;
    }
    
}
