<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?=Yii::$app->user->identity->avatar?>" class="img-circle" alt="User Image"/>
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

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Общее', 'options' => ['class' => 'header']],
                        ['label' => 'Каталог товаров', 'icon' => 'bars', 'url' => '/admin/category/'],
                    ['label' => 'Администрирование', 'options' => ['class' => 'header']],
                    ['label' => 'Пользователи', 'icon' => 'users', 'url' => ['/user']],
                    ['label' => 'Клиенты', 'icon' => 'users', 'url' => ['/client']],
                    ['label' => 'Для разработчика', 'options' => ['class' => 'header']],
                    [
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
                            ['label' => 'PriceType', 'url' => ['/priceType'],],
                            ['label' => 'Product', 'url' => ['/product']],
                            ['label' => 'User', 'url' => ['/user']],
                            ['label' => 'Category', 'url' => ['/category']],
                            ['label' => 'ProdcutCategory', 'url' => ['/product-category']],
                        ],
                    ],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
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
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
