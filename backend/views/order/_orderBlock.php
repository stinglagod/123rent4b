<?php
use yii\widgets\Pjax;
use kartik\editable\Editable;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 20.02.2019
 * Time: 14:40
 */
/* @var $block common\models\OrderProduct[] */
/* @var $modelBlock common\models\OrderBlock */
//echo "<pre>";
//var_dump($block);
//echo "</pre>";exit;
$modelBlock=$block['orderBlock'];
$htmlId='block_'.rand();

?>
<div class="panel panel-default col-md-12" id="block_<?=$modelBlock->id?>">
    <div class="panel-heading row">
        <div class="col-md-1">
            <a class="btn btn-primary" data-toggle="collapse" href="#<?=$htmlId?>" role="button" aria-expanded="true" aria-controls="<?=$htmlId?>">
                <i class="glyphicon glyphicon-minus"></i>
                <i class="glyphicon glyphicon-plus"></i>
            </a>
        </div>
        <div class="col-md-5 col-sm-11 col-xs-6">
<!--            <h4>--><?//=$modelBlock->name;?><!--</h4>-->
            <h4>
            <?=Editable::widget([
                'model' => $modelBlock,
                'attribute' => 'name',
//                'beforeInput'=>function($form, $widget) {
//                    echo Html::hiddenInput('stage_id', $widget->model->id);
//                },
                'asPopover' => false,
                'value' => '<h4>'.$modelBlock->name.'</h4>',
                'header' => 'Название блока',
                'format' => Editable::FORMAT_BUTTON,
                'formOptions' => [
                    'action' => Yii::$app->request->baseUrl.'/order-block/update-ajax?id='.$modelBlock->id,
                    'method' => 'post',
                ],
                'options' => [
                    'class'=>'form-control',
                    'prompt'=>'Блок',
                    'id'=> 'order-block_id'.$modelBlock->id,
                ],
//            'editableValueOptions'=>['class'=>'text-danger']
            ])?></h4>
        </div>
        <div class="col-md-6">
                <div class="btn-group  pull-right" role="group" aria-label="toolbar">
                    <button class="btn btn-default lst_addproduct" data-block="<?=$htmlId?>" data-block_id="<?=$modelBlock->id?>" type="button" ><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Добавить позицию</button>
                    <button class="btn btn-default lst_addproduct" data-block="<?=$htmlId?>" data-block_id="<?=$modelBlock->id?>" data-parent_id='new' type="button" ><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Добавить составную позицию</button>
                    <button class="btn btn-default lst_deleteblock" data-block="<?=$htmlId?>" data-block_id="<?=$modelBlock->id?>" type="button"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                </div>
        </div>
    </div>
    <div class="collapse in" aria-expanded="true" id="<?=$htmlId?>">
        <div class="panel-body" >
            <?=$this->render('_gridOrderProduct',[
                'dataProvider'=>$block['dataProvider'],
                'orderBlock_id'=>$block['orderBlock']->id
            ])
            ?>
        </div>
    </div>
</div>