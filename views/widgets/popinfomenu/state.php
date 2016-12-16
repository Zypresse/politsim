<?php

use app\components\LinkCreator;

/* @var $state \app\models\politics\State */
/* @var $states \app\models\politics\State[] */

?>
<div class="box box-info">
    <div class="box-body">
        <div class="box-group">
            <?php foreach ($states as $currentState): ?>
            <div class="box box-solid <?=($currentState->id == $state->id)?'box-primary':''?>">
                <div class="box-header">
                    <h4 class="box-title" style="font-size: 14px;">
                        <?= LinkCreator::statePopulationLink($currentState) ?>
                    </h4>
                </div>
                <?php if ($currentState->id == $state->id): ?>
                <div class="box-body">
                    <div class="box-group">
                    <?php foreach ($state->regions as $currentRegion): ?>
                        <div class="box box-solid">
                            <div class="box-header">
                                <h4 class="box-title" style="font-size: 16px;">
                                    <?= LinkCreator::regionPopulationLink($currentRegion) ?>
                                </h4>
                            </div>
                        </div>
                    <?php endforeach ?>
                    </div>
                </div>
                <?php endif ?>
            </div>
            <?php endforeach ?>
        </div>
    </div>
</div>