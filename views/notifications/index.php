<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper;

/* @var $this \yii\web\View */
/* @var $notifications \app\models\Notification[] */
/* @var $user \app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Notifications')?>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-user"></i> <a href="#!profile"><?=Html::encode($user->name)?></a></li>
        <li class="active"><?=Yii::t('app', 'Notifications')?></li>
    </ol>
</section>
<section class="content">
    <?php if (count($notifications)): ?>
    <ul class="timeline ">
        <?php        
            $readedStarted = false;
            foreach ($notifications as $i => $notification):
        ?>
        <?php if ($i == 0 && is_null($notification->dateReaded)): ?>
        <li class="time-label">
            <span class="bg-red">
                <?=Yii::t('app', 'New notifications')?>
            </span>
        </li>
        <?php elseif (!$readedStarted): 
            $readedStarted = true; ?>
        <li class="time-label">
            <span class="bg-gray">
                <?=Yii::t('app', 'Readed')?>
            </span>
        </li>
        <?php endif ?>
        <li>
            <?=$notification->getIconBg()?>

            <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> <?=MyHtmlHelper::timeAutoFormat($notification->dateCreated)?></span>

                <h3 class="timeline-header"><?=$notification->getTextShort()?></h3>

                <div class="timeline-body">
                    <?=$notification->getText()?>
                </div>
                <div class="timeline-footer">
                    
                </div>
            </div>
        </li>
        <?php endforeach ?>
        <li>
            <i class="fa fa-clock-o bg-gray"></i>
        </li>
    </ul>
    <?php else: ?>
    <p>
        <?=Yii::t('app', 'No one notification')?>
    </p>
    <?php endif ?>
</section>