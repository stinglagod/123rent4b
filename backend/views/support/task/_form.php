<?php

use backend\widgets\ButtonGroup;
use kartik\select2\Select2;
use rent\entities\Support\Task\Task;
use rent\entities\User\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \rent\forms\support\task\TaskForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-primary">
        <div class="box-header with-border">Общее</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'type')->radioList(
                        Task::getTypeLabels()
                    ) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'priority')->widget(ButtonGroup::class, [
                        'default'  => 0,
                        'items'    => Task::getPriorityLabels(),
                        'btnClass' => 'btn-default btn-sm',
                        'data'     => ['original' => $model->priority, 'new' => (int)$model->isNew()]
                    ])->label(false) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'client_id')->widget(Select2::class, [
                        'data' => $model->getClientsList(),
                        'options' => ['placeholder' => '', 'multiple' => false,],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'text')->textarea(['maxlength' => true]) ?>
                </div>
            </div>

        </div>
    </div>

    <?if (Yii::$app->user->can('super_admin')):?>
    <div class="box box-warning">
        <div class="box-header with-border">Администрирование</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'responsible_id')->dropDownList(User::getResponsibleList(), ['prompt' => Yii::t('app', 'Выберите')]) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'status')->dropDownList(Task::getStatusLabels()) ?>
                </div>
                <div class="col-md-1">
                    <?=$form->field($model, 'is_completed')->checkbox(['maxlength' => true]) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'commentClosed')->textarea(['maxlength' => true]) ?>
                </div>
            </div>

        </div>
    </div>
    <?endif;?>

    <div class="form-group">
        <?= Html::submitButton($model->isNew()?'Создать':'Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
