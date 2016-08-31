<?php

/* @var $this yii\base\View */

?>
<h1>список гражданств</h1>
<?php foreach ($list as $citizenship): ?>
<?=$citizenship->dateCreated()?>
<?php endforeach ?>
