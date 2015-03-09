<?php

/* 
 * Copyleft license
 * I dont care how you use it
 */

use app\components\MyHtmlHelper;

?>
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