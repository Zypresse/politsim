<h1>Это государство более не существует</h1>
<?php
    if ($state_id === $user->state_id) {
        ?>
<button class="btn btn-lg btn-warning" onclick="if (confirm('Вы действительно хотите от гражданства?')) { json_request('drop-citizenship',{});  }">Отказаться от гражданства</button>
        <?php
    }