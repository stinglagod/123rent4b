<?php
/**
 * Вывод левого каталога
 */
use yii\helpers\Url;
use \yii\helpers\Html;
?>
<div class="categories-menu mrg-xs">
    <div class="category-heading">
        <h3> Каталог</h3>
    </div>
    <div class="category-menu-list">
        <ul>
            <?php
            foreach ($categories['children'] as $level1) {
                ?>
                <li><a href="<?=Url::toRoute(['/shop/catalog/category', 'id' => $level1['id']])?>"><?=Html::encode($level1['name'])?><?=$level1['children']?'<i class="zmdi zmdi-chevron-right"></i>':''?></a>
                    <?php
                    if ($level1['children']){
                        ?>
                        <div class="category-menu-dropdown">
                            <?php
                            foreach ($level1['children'] as $level2) {
                                ?>
                                <div class="category-part-1 category-common mb--30">
                                    <a href="<?=Url::toRoute(['/shop/catalog/category', 'id' => $level2['id']])?>"><h4 class="categories-subtitle"> <?=Html::encode($level2['name'])?></h4></a>
                                    <?php
                                    if($level2['children']){
                                        ?>
                                        <ul>
                                            <?php
                                            foreach ($level2['children'] as $level3) {
                                                ?>
                                                <li><a href="<?=Url::toRoute(['/shop/catalog/category', 'id' => $level3['id']])?>"><?=Html::encode($level3['name'])?></a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>
