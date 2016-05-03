
<section class="content">
    <div class="row">
        <div class="col-md-12">
            
        <h1>Это государство более не существует</h1>
<?php
    if ($state_id === $user->state_id) {
        ?>
<button class="btn btn-lg btn-danger" onclick="if (confirm('Вы действительно хотите от гражданства?')) { json_request('drop-citizenship',{});  }">Отказаться от гражданства</button>
        <?php
    }?>

        </div>
    </div>
</section>