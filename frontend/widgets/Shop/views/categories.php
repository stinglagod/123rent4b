<?php
/**
 * Вывод левого каталога
 */
use yii\helpers\Url;
use yii\helpers\Html;
use rent\readModels\Shop\views\CategoryView;
use rent\entities\Shop\Category;

/* @var $categories[] CategoryView */
?>
<div class="categories-menu mrg-xs">
    <div class="category-heading">
        <h3> Каталог</h3>
    </div>
    <div class="category-menu-list">
        <ul>
            <?php foreach ($categories['children'] as $level1) :?>
                <?php if ($level1['count']==0) continue;?>
                <li><a href="<?=Url::toRoute(['/shop/catalog/category', 'id' => $level1['id']])?>"><?=Html::encode($level1['name'])?><?=$level1['children']?'<i class="zmdi zmdi-chevron-right"></i>':''?></a>
                    <?php if ($level1['children']):?>
                        <div class="category-menu-dropdown">
                            <?php foreach ($level1['children'] as $level2) :?>
                                <?php if ($level2['on_site']) : ?>
                                    <div class="category-part-1 category-common mb--30">
                                        <a href="<?=Url::toRoute(['/shop/catalog/category', 'id' => $level2['id']])?>"><h4 class="categories-subtitle"> <?=Html::encode($level2['name'])?></h4></a>
                                        <?php if($level2['children']):?>
                                            <ul>
                                                <?php foreach ($level2['children'] as $level3) :?>
                                                    <li><a href="<?=Url::toRoute(['/shop/catalog/category', 'id' => $level3['id']])?>"><?=Html::encode($level3['name'])?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif;?>
                                    </div>
                                <?php endif;?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif;?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
