<?php

use app\models\politics\bills\BillProto;

/* @var $this yii\base\View */
/* @var $model app\models\politics\bills\Bill */
/* @var $post app\models\politics\AgencyPost */
/* @var $types array */

$typesByCategories = [];
foreach ($types as $id => $name) {
    $category = BillProto::getCategory($id);
    if (isset($typesByCategories[$category])) {
        $typesByCategories[$category][$id] = $name;
    } else {
        $typesByCategories[$category] = [
            $id => $name
        ];
    }
}

?>
<div class="box-group">
    <?php foreach ($typesByCategories as $category => $types): ?>
    <div class="box">
        <div class="box-header">
            <h4 class="box-title"><?=$category?></h4>
        </div>
        <div class="box-body">
            <div class="btn-group">
            <?php foreach ($types as $id => $name): ?>
                <button class="btn btn-default new-bill-type-btn" data-id="<?=$id?>">
                    <?=$name?>
                </button>
            <?php endforeach ?>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>
<script type="text/javascript">
    
    $('.new-bill-type-btn').click(function(){
        load_modal(
            'work/new-bill-form',
            {postId:<?=$post->id?>, protoId:$(this).data('id')},
            'work-new-bill-form-modal'
        );
    });

</script>