<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\widgets\Shop\CategoriesWidget;

?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>


<?=$this->render('_breadcrumb');?>
<section class="categories-slider-area bg__white">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-lg-3 col-sm-4 col-xs-12 ">
                <?= CategoriesWidget::widget([
                    'active' => $this->params['active_category'] ?? null
                ]) ?>
            </div>
            <div class="col-md-9 col-lg-9 col-sm-8 col-xs-12">
                <?= $content ?>
            </div>
        </div>
    </div>
</section>

<?php $this->endContent() ?>
