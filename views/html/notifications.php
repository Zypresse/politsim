<?php

/* 
 * Copyleft license
 * I dont care how you use it
 */

use app\components\MyHtmlHelper;

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
<table class="table">
<?
foreach ($user->notifications as $not) {
?>
    <tr>
        <td>
            <?=$not->text?>
        </td>
    </tr>
<?
}
?>
</table>
        </div>
    </div>
</div>