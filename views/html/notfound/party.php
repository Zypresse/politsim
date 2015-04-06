<h1>Эта партия более не существует</h1>
<?php
    if ($party_id === $user->party_id) {
        ?>
<button class="btn btn-lg btn-warning" onclick="if (confirm('Вы действительно хотите выйти из партии?')) { json_request('leave-party',{});  }">Отказаться от гражданства</button>
        <?
    }