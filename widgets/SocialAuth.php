<?php

namespace app\widgets;

use yii\base\Widget;

/**
 * Description of SocialAuth
 *
 * @author ilya
 */
class SocialAuth extends Widget
{
    
    public $inline = false;
    
    public function run()
    {
        return $this->render('social-auth', [
            'inline' => $this->inline,
        ]);
    }
    
}
