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
                ['label' => 'Магазин', 'icon' => 'shopping-bag', 'items' => [
                    ['label' => 'Каталог', 'icon' => 'tree', 'url' => ['/shop/catalog/'], 'active' => $this->context->id == 'shop/catalog'],
                    ['label' => 'Заказы', 'icon' => 'fa', 'url' => ['/shop/order/index'], 'active' => $this->context->id == 'shop/order'],
                    ['label' => 'Касса', 'icon' => 'money', 'url' => ['/shop/cashbox/'], 'active' => $this->context->id == 'shop/cashbox'],
                    ['label' => 'Бренды', 'icon' => 'vine', 'url' => ['/shop/brand/index'], 'active' => $this->context->id == 'shop/brand'],
                    ['label' => 'Теги', 'icon' => 'tags', 'url' => ['/shop/tag/index'], 'active' => $this->context->id == 'shop/tag'],
                    ['label' => 'Характеристики', 'icon' => 'sliders', 'url' => ['/shop/characteristic/index'], 'active' => $this->context->id == 'shop/characteristic'],
                    ['label' => 'Услуги', 'icon' => 'server', 'url' => ['/shop/service/index'], 'active' => $this->context->id == 'shop/service'],

                ]],
                ['label' => 'CRM', 'icon' => 'folder', 'items' => [
                    ['label' => 'Контакты', 'icon' => 'address-card', 'url' => ['/crm/contact/index'], 'active' => $this->context->id == 'crm/contact'],
                ]],
                ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
            ];
            if (Yii::$app->user->can('admin')) {
                $items[]=['label' => 'Сайт', 'icon' => 'internet-explorer', 'items' => [
                    ['label' => 'Страницы', 'icon' => 'file-o', 'url' => ['/page/index'], 'active' => $this->context->id == 'page']
                ]];
            }

            if ((Yii::$app->user->can('manager')) ) {
                $items[] = ['label' => 'Тех.поддержка', 'icon' => 'support', 'items' => [
                    ['label' => 'Создать заявку', 'icon' => 'plus-circle', 'url' => ['/support/task/create'], 'active' => $this->context->id == 'support/task/create'],
                    ['label' => 'Заявки', 'icon' => 'tasks', 'url' => ['/support/task/index'], 'active' => $this->context->id == 'support/task'],
                ]];
            }

            if ((Yii::$app->user->can('super_admin')) ) {

                $items[]=['label' => 'Управление', 'icon' => 'toggle-on', 'items' => [
                    ['label' => 'Пользователи', 'icon' => 'group', 'url' => ['/user/index'], 'active' => $this->context->id == 'user/index'],
                    ['label' => 'Клиенты', 'icon' => 'user', 'url' => ['/client/client/index'], 'active' => $this->context->id == 'client/index']
                ]];

                $items[]=['label' => 'Для разработчика', 'options' => ['class' => 'header']];
                $items[]=['label' => 'php-info', 'icon' => 'dashboard', 'url' => ['/php-info']];
                $items[]=[
                    'label' => 'Отладка',
                    'icon' => 'share',
                    'url' => '#',
                    'items' => [
                        ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                        ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                        ['label' => 'php-info', 'icon' => 'dashboard', 'url' => ['/php-info']],
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
