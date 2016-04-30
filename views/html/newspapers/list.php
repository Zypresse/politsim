<?php

use app\models\State,
    app\models\massmedia\Massmedia,
    app\components\MyHtmlHelper;

/* @var $states State[] */
/* @var $newspapers Massmedia[] */

?>
<div class="container">
    <div class="row" style="margin-top: 10px">
        <div class="col-md-12">
            <div class="btn-group">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" >По стране <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="#">Все страны</a></li>
                    <li role="separator" class="divider"></li>
                    <?php foreach($states as $state):?>
                    <li><?=MyHtmlHelper::a($state->getHtmlName(false),'load_page("newspapers",{"stateId":'.$state->id.'})')?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>
</div>