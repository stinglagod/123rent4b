<?php
/* @var $this \yii\web\View */
use yii\widgets\Breadcrumbs;
?>
<!-- Start Bradcaump area -->
<div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(/images/bg/2.jpg) no-repeat scroll center center / cover ;">
    <div class="ht__bradcaump__wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-4">
                </div>
                <div class="col-xs-8">
                    <div class="bradcaump__inner text-right">
                        <?php if (!empty($this->params['h1'])) : ?>
                        <h1 class="bradcaump-title"><?=$this->params['h1']?></h1>
                        <?php endif;?>

                        <?=
                        Breadcrumbs::widget([
                            'options'       =>  [
                                'id'        =>  'breadCrumbs',
                                'class'         =>  "bradcaump-inner"
                            ],
                            // settings of home link and display
                            'homeLink'      =>  [
                                'label'     =>  Yii::t('yii', 'Home'),
                                'url'       =>  ['/site/index'],
                                'class'     =>  'breadcrumb-item',
                                'template'  =>  '{link}',
                            ],
                            'links'         =>  $this->params['breadcrumbs'],
                            'itemTemplate'  =>  '<span class="brd-separetor">/</span><span class="breadcrumb-item active">{link}</span>',
                            'tag'           =>  'nav',
                            'activeItemTemplate' => '<span class="brd-separetor">/</span><span class="breadcrumb-item active">{link}</span>',

                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Bradcaump area -->