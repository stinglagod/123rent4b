<?php
/**
 * Вывод левого каталога
 */
use yii\widgets\Menu;

?>

<!--<div class="col-md-3 col-lg-3 col-sm-4 col-xs-12 float-right-style">-->
    <div class="categories-menu mrg-xs">
        <div class="category-heading">
            <h3> Каталог</h3>
        </div>
        <div class="category-menu-list">
            <ul>
                <?php
                    foreach ($menuCatalogItems['children'] as $level1) {
                ?>
                        <li><a href="<?=\yii\helpers\Url::toRoute(['catalog/']).$level1['alias'].'/'?>"><img alt="" src="/images/icons/thum2.png"><?=$level1['name']?><?=$level1['children']?'<i class="zmdi zmdi-chevron-right"></i>':''?></a>
                        <?php
                            if ($level1['children']){
                        ?>
                            <div class="category-menu-dropdown">
                            <?php
                                foreach ($level1['children'] as $level2) {
                            ?>
                                <div class="category-part-1 category-common mb--30">
                                    <a href="<?=\yii\helpers\Url::toRoute(['catalog/']).$level2['alias'].'/'?>" ><h4 class="categories-subtitle"> <?=$level2['name']?></h4></a>
                                    <?php
                                        if($level2['children']){
                                    ?>
                                        <ul>
                                        <?php
                                            foreach ($level2['children'] as $level3) {
                                        ?>
                                                <li><a href="<?=\yii\helpers\Url::toRoute(['catalog/']).$level3['alias'].'/'?>"><?=$level3['name']?></a></li>
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
<!--</div>-->
