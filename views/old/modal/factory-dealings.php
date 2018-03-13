<?php

/* @var $factory app\models\factories\Factory */

use app\components\widgets\DealingsListWidget;

?>
<?=DealingsListWidget::widget(['dealings' => $factory->getDealings(10)]);?>
