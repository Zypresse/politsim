<?php

namespace app\components;

/**
 * Description of LinkHelper
 *
 * @author dev
 */
abstract class LinkHelper {
    
    /**
     * 
     * @param string $link
     * @return boolean
     */
    public static function isImageLink($link)
    {
        $ar = explode('.', trim($link));
        
        return (is_array($ar) && count($ar)>1 && in_array(end($ar),['jpg','png','gif','jpeg']));
    }
    
    /**
     * 
     * @param string $link
     * @return boolean
     */
    public static function isSoundCloudLink($link)
    {
        $re = "/https:\\/\\/soundcloud\\.com\\/[A-z\\d\\-]*\\/[A-z\\d\\-]*/i"; 
        return !!preg_match($re, $link);
    }
    
}
