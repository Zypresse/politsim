<?php

use app\components\LinkCreator,
    app\models\politics\elections\ElectionRequestType,
    yii\helpers\Html;

/* @var $this yii\base\View */
/* @var $election app\models\politics\elections\Election */
?>
<h5><?= Yii::t('app', 'Elections of agency post {0}', [Html::encode($election->whom->name)]) ?></h5>
<table class="table table-bordered table-striped">
<?php foreach ($election->requests as $request): ?>
    <tr>
        <td class="text-center" style="width:50px;">
            <input value="<?=$request->variant?>" type="radio" name="election-variant" id="election-variant-<?= $request->variant ?>" class="election-variant-radio">
        </td>
        <td>
            <?php if ($request->type != ElectionRequestType::NONE_OF_THE_ABOVE): ?>
            <?= LinkCreator::link($request->object)?>
            <?php endif ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>
<p class="help-block"><?=Yii::t('app', 'Check one variant and click «Vote»')?></p>
<input type="hidden" id="election-variant-selected">
<script>
    $(function () {
        $('.election-variant-radio').iCheck({
            checkboxClass: 'icheckbox_square',
            radioClass: 'iradio_square',
            increaseArea: '20%' // optional
        }).on('ifChecked', function () {
            $('#election-variant-selected').val($(this).val());
            $('.election-vote-confirm-btn').removeAttr('disabled');
        });
    });
</script>