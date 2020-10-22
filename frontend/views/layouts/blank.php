<?php

/* @var $this \yii\web\View */
/* @var $content string */
//$this->title = '';
//$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>
<?=$this->render('_breadcrumb');?>
<div class="row">
    <div id="content" class="col-sm-12">
        <?= $content ?>
    </div>
</div>

<?php $this->endContent() ?>
