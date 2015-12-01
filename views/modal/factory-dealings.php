<?php

/* @var $factory app\models\factories\Factory */

use app\components\widgets\DealingsListWidget;

echo DealingsListWidget::widget(['dealings' => $factory->getDealings(10)]);
