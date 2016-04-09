<div class="container">
    <div class="row">
        <div class="col-md-12">
<table class="table">
<?php foreach ($user->notifications as $not): ?>
    <tr>
        <td>
            <?=$not->text?>
        </td>
    </tr>
<?php endforeach ?>
</table>
        </div>
    </div>
</div>