<?php
use yii\helpers\Html;
?>
<!-- Start Search Popap -->
<div class="search__area">
    <div class="container" >
        <div class="row" >
            <div class="col-md-12" >
                <div class="search__inner">
                    <?= Html::beginForm(['/shop/catalog/search'], 'get') ?>
                        <input placeholder="Поиск здесь... " type="text">
                        <button type="submit"></button>
                    <?= Html::endForm() ?>
                    <div class="search__close__btn">
                        <span class="search__close__btn_icon"><i class="zmdi zmdi-close"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Search Popap -->