<?php

use rent\entities\Client\Client;

?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?=Yii::$app->user->identity->avatarUrl?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=Yii::$app->user->identity->shortName?></p>


                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Поиск..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?
            $items=[
                ['label' => 'Управление', 'options' => ['class' => 'header']],
                ['label' => 'Магазин', 'icon' => 'folder', 'items' => [
                    ['label' => 'Каталог', 'icon' => 'file-o', 'url' => ['/shop/catalog/index'], 'active' => $this->context->id == 'shop/catalog'],
                    ['label' => 'Заказы', 'icon' => 'file-o', 'url' => ['/shop/order/index'], 'active' => $this->context->id == 'shop/order'],
                    ['label' => 'Бренды', 'icon' => 'file-o', 'url' => ['/shop/brand/index'], 'active' => $this->context->id == 'shop/brand'],
                    ['label' => 'Теги', 'icon' => 'file-o', 'url' => ['/shop/tag/index'], 'active' => $this->context->id == 'shop/tag'],
                    ['label' => 'Характеристики', 'icon' => 'file-o', 'url' => ['/shop/characteristic/index'], 'active' => $this->context->id == 'shop/characteristic'],

                ]],
                ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
            ];
            if (Yii::$app->user->can('admin')) {
                $items[]=['label' => 'Страницы', 'icon' => 'file-o', 'url' => ['/page/index'], 'active' => $this->context->id == 'page'];
            }
            if ((Yii::$app->user->can('super_admin')) ) {
                $items[]=['label' => 'Пользователи', 'icon' => 'user', 'url' => ['/user/index'], 'active' => $this->context->id == 'user/index'];
                $items[]=['label' => 'Клиенты', 'icon' => 'user', 'url' => ['/client/client/index'], 'active' => $this->context->id == 'client/index'];
                $items[]=['label' => 'Для разработчика', 'options' => ['class' => 'header']];
                $items[]=[
                    'label' => 'CRUD',
                    'icon' => 'file-code-o',
                    'url' => '#',
                    'items' => [
                        ['label' => 'Action', 'url' => ['/action'],],
                        ['label' => 'Cash', 'url' => ['/cash'],],
                        ['label' => 'Client', 'url' => ['/client'],],
                        ['label' => 'ClientUser', 'url' => ['/client-user'],],
                        ['label' => 'File', 'url' => ['/file'],],
                        ['label' => 'Movement', 'url' => ['/movement'],],
                        ['label' => 'Order', 'url' => ['/order'],],
                        ['label' => 'OrderCash', 'url' => ['/order-cash'],],
                        ['label' => 'OrderProduct', 'url' => ['/order-product'],],
                        ['label' => 'OrderProductAction', 'url' => ['/order-product-action'],],
                        ['label' => 'Ostatok', 'url' => ['/ostatok'],],
                        ['label' => 'PeriodType', 'url' => ['/periodType'],],
                        ['label' => 'Product', 'url' => ['/product']],
                        ['label' => 'User', 'url' => ['/user']],
                        ['label' => 'Category', 'url' => ['/category']],
                        ['label' => 'ProductCategory', 'url' => ['/product-category']],
                        ['label' => 'Tag', 'url' => ['/tag']],
                        ['label' => 'Attribute', 'url' => ['/attribute']],
                        ['label' => 'ProductAttribute', 'url' => ['/product-attribute']],
                        ['label' => 'OrderBlock', 'url' => ['/order-block']],
                        ['label' => 'Block', 'url' => ['/block']],
                        ['label' => 'CashType', 'url' => ['/cash-type']],
                        ['label' => 'Status', 'url' => ['/status']],
                        ['label' => 'Service', 'url' => ['/services']],
                    ],
                ];
                $items[]=['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']];
                $items[]=['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']];
                $items[]=['label' => 'php-info', 'icon' => 'dashboard', 'url' => ['/php-info']];
                $items[]=['label' => 'php-info', 'icon' => 'dashboard', 'url' => ['/php-info']];
                $items[]=[
                    'label' => 'Some tools',
                    'icon' => 'share',
                    'url' => '#',
                    'items' => [
                        ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                        ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                        [
                            'label' => 'Level One',
                            'icon' => 'circle-o',
                            'url' => '#',
                            'items' => [
                                ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                [
                                    'label' => 'Level Two',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ];
            }


        ?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => $items,
            ]
        ) ?>

    </section>

</aside>
