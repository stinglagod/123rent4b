<?php
use yii\widgets\Pjax;
use kartik\editable\Editable;
use yii\helpers\Html;
use yii\helpers\Url;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 20.02.2019
 * Time: 14:40
 */
/* @var $block \rent\entities\Shop\Order\Item\OrderItem */
/* @var $model \rent\forms\manage\Shop\Order\Item\BlockForm */

//echo "<pre>";
//var_dump($block);
//echo "</pre>";exit;
$htmlId='block_'.rand();

?>
<div class="panel panel-default col-md-12 item-block" id="<?=$block->block_id?>">
    <div class="panel-heading row">
        <div class="col-md-1">
            <a class="btn btn-primary" data-toggle="collapse" href="#<?=$htmlId?>" role="button" aria-expanded="true" aria-controls="<?=$htmlId?>">
                <i class="glyphicon glyphicon-minus"></i>
                <i class="glyphicon glyphicon-plus"></i>
            </a>
        </div>
        <div class="col-md-5 col-sm-11 col-xs-6">
            <?=Editable::widget([
                'model' => $model,
                'attribute' => 'name',
                'asPopover' => false,
                'value' => '<h4>'.Html::encode($block->name).'</h4>',
                'header' => 'Название блока',
                'format' => Editable::FORMAT_BUTTON,
                'formOptions' => [
                    'action' => ['block-update-ajax','id'=>$block->order_id,'block_id'=>$block->block_id],
                    'method' => 'post',
                ],
                'options' => [
                    'class'=>'form-control',
                    'prompt'=>'Блок',
                    'id'=> 'order-block_id'.$block->id,
                ],
            ])?>
        </div>
        <div class="col-md-6">
                <div class="btn-group  pull-right" role="group" aria-label="toolbar">
                    <button class="btn btn-default lst_addproduct" data-block="<?=$htmlId?>" data-block_id="<?=$block->id?>" type="button" ><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Добавить позицию</button>
                    <button class="btn btn-default lst_addproduct" data-block="<?=$htmlId?>" data-block_id="<?=$block->id?>" data-parent_id='new' type="button" ><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Добавить составную позицию</button>
                    <button class="btn btn-default lst_delete-block" data-url="<?=Url::toRoute(['block-delete-ajax','id'=>$block->order_id,'block_id'=>$block->block_id])?>" data-method="POST"  data-block_id="<?=$block->block_id?>" type="button"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                    <?php if ($block->sort!=0):?>
                    <button class="btn btn-default move-block" data-url="<?=Url::toRoute(['block-move-down-ajax','id'=>$block->order_id,'block_id'=>$block->block_id])?>" data-method="POST" type="button"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></button>
                    <?php endif ?>
                    <?php if ($block->sort!=($block->order->countBlocks()-1)):?>
                    <button class="btn btn-default move-block" data-url="<?=Url::toRoute(['block-move-up-ajax','id'=>$block->order_id,'block_id'=>$block->block_id])?>" data-method="POST" type="button"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span></button>
                    <?php endif ?>
                </div>
        </div>
    </div>
    <div class="collapse in" aria-expanded="true" id="<?=$htmlId?>">
        <div class="panel-body" >
<!--            --><?//=$this->render('_gridOrderProduct',[
//                'dataProvider'=>$block['dataProvider'],
//                'orderBlock_id'=>$block['orderBlock']->id
//            ])
//            ?>
        </div>
    </div>
</div>