<?php

namespace app\helpers;

/**
 * Description of SoundCloudLink
 *
 * @author ilya
 */
class SoundCloudLink
{
    
    /**
     * 
     * @param string $link
     * @return boolean
     */
    public static function validate($link)
    {
        $re = "/https:\\/\\/soundcloud\\.com\\/[A-z\\d\\-]*\\/[A-z\\d\\-]*/i"; 
        return !!preg_match($re, $link);
    }
    
}
