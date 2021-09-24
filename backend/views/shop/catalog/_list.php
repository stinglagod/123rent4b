<?php

use \yii\widgets\ListView;
use yii\helpers\Html;
use \yii\widgets\ActiveForm;
use kartik\editable\Editable;
use yii\helpers\Url;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $category \rent\entities\Shop\Category\Category */
/* @var $order \rent\entities\Shop\Order\Order */


$layout="<div class='nav-cat clearfix'>
                            <div class='pull-left'>
                                {pager}
                            </div>
                            {summary}
                         </div>
                         <div class='lst row'>
                            {items}
                         </div>
                         <div class='nav-cat clearfix'>
                            <div class='pull-left'>
                                {pager}
                            </div>
                            {summary}
                         </div>
                        ";
?>
<div class="row">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_product',
        'layout' => $layout,
        'summary' => "<div class='pull-left nam-page'>Показано c {begin} по {end} из {totalCount} (всего {pageCount} страниц)</div>",
        'summaryOptions' => [
            'tag' => 'div',
            'class' => 'pull-left nam-page',
        ],
        'options' => [
            'tag' => 'div',
            'id' => 'productList',
        ],
        'itemOptions' => [
            'tag' => 'div',
            'class' => 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12',
        ],
        'viewParams' => [
            'order'=>$order,
        ],
    ]) ?>
</div>