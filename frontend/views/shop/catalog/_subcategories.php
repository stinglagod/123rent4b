<?php

/* @var $category shop\entities\Shop\Category */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php if ($category->children): ?>
<!--    <div class="category-heading ptb--30">-->
    <div class="product-tab-list text-center">
    <ul class="tab-style" role="tablist">
<!--        <div class="panel-body">-->
            <?php foreach ($category->children as $child): ?>
                <li>
                    <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'id' => $child->id])) ?>">  <div class="tab-menu-text"><h4><?= Html::encode($child->name) ?></h4></div></a>
                </li>
            <?php endforeach; ?>
<!--        </div>-->
    </ul>
    </div>
<!--    </div>-->
<?php endif; ?>

