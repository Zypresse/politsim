<?php

namespace app\models\politics\constitution\articles\postsonly;

use app\models\politics\constitution\articles\base\UnsignedIntegerArticle;

/**
 * Срок полномочий
 */
class TermsOfOffice extends UnsignedIntegerArticle
{
    
    use \app\models\politics\constitution\articles\base\NoSubtypesArticle;
    
    
}
