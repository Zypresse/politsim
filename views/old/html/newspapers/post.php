<?php

use app\models\User,
    app\models\massmedia\Massmedia,
    app\components\MyHtmlHelper,
    yii\helpers\Html,
    yii\helpers\ArrayHelper;

/* @var $newspaper Massmedia */
/* @var $user User */

?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form id="new-post-form" method="POST" action="" >
                <?=Html::input('hidden','id',$newspaper->id)?>
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">
                            <i class="fa fa-newspaper-o"></i> <?=$newspaper->getHtmlName()?>
                            <small>Новый пост</small>
                        </h3>
                        <div class="pull-right box-tools">
                        </div>
                    </div>
                    <div class="box-body pad">
                        <div class="form-group">
                            <?=Html::input('text', 'MassmediaPost[title]', '', ['placeholder' => 'Заголовок', 'class' => 'form-control', 'id' => 'new-post-title'])?>
                        </div>
                        <textarea id="editor1" name="MassmediaPost[text]" rows="10" cols="80" ></textarea>
                    </div>
                    <div class="box-footer">                    
                        <div class="btn-group pull-right">
                            <?=Html::submitButton('Опубликовать', ['class' => 'btn btn-primary'])?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('editor1');
        
        $('#new-post-form').submit(function(){
            json_post_request('newspaper-post', $(this).serializeObject(), true, false, function(){
                load_page('newspaper-feed', {'id':<?=$newspaper->id?>});
            });
            return false;
        });
        
        $('#new-post-title').focus();
    });
</script>