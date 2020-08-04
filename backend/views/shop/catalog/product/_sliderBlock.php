<?php

/* @var $product \rent\entities\Shop\Product\Product */

?>

<div class="row" style="width: 1000px; padding: 30px;">
    <div class="col-md-12 center-block" >
        <?php if ($product->mainPhoto):?>
            <ul id="imageGallery">
                <li data-thumb='<?=$product->mainPhoto->getThumbFileUrl('file', 'thumb')?>' data-src='<?=$product->mainPhoto->getThumbFileUrl('file', 'thumb')?>'>
                    <img src='<?=$product->mainPhoto->getThumbFileUrl('file', 'thumb')?>' class='center-block'/>
                </li>
                <?php foreach ( $product->photos as $photo): ?>
                    <li data-thumb='<?=$photo->getThumbFileUrl('file', 'thumb')?>' data-src='<?=$photo->getThumbFileUrl('file', 'thumb')?>'>
                        <img src='<?=$photo->getThumbFileUrl('file', 'thumb')?>' class='center-block'/>
                    </li>
                <?php endforeach;?>
            </ul>
        <?php endif;?>
    </div>
    <button class="btn btn-default uplImgProduct center-block"  type="button"><i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i>Загрузить изображения</button></div>
</div>

