<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = \Yii::$app->authManager;
        $auth->removeAll();

//        // Создаем разрешения
//        // Вход на сайт
//        $login  = $auth->createPermission('login');
//        $login->description = 'Login';
//        $auth->add($login);
//        //Выход
//        $logout = $auth->createPermission('logout');
//        $logout->description = 'Logout';
//        $auth->add($logout);
//        //Регистрация
//        $signUp = $auth->createPermission('sign-up');
//        $signUp->description = 'Sing up';
//        $auth->add($signUp);
//        //Публичная часть сайта
//        $index = $auth->createPermission('index');
//        $index->description = 'Index';
//        $auth->add($index);
//        //Админ панель сайта
//        $indexAdmin = $auth->createPermission('index-admin');
//        $indexAdmin->description = 'Index admin';
//        $auth->add($indexAdmin);
//
//        //
//
//        // Создаем роли
//        //Неавторизированный пользователь
//        $guest  = $auth->createRole('guest');
//        $guest->description = 'Не зарегистрированный';
//        $auth->add($guest);
//        //Назначим разрешения для роли
//        $auth->addChild($guest, $login);
//        $auth->addChild($guest, $signUp);
//
//        //Зарегистрированный пользователь
//        $user  = $auth->createRole('user');
//        $user->description = 'Пользователь';
//        $auth->add($user);
//        //Назначим разрешения для роли
//        $auth->addChild($user, $guest);
//        $auth->addChild($user, $logout);
//        $auth->addChild($user, $index);
//
//        //Менеджер
//        $manager  = $auth->createRole('manager');
//        $manager->description = 'Менеджер';
//        $auth->add($manager);
//        //Назначим разрешения для роли
//        $auth->addChild($manager, $user);
//        $auth->addChild($manager, $indexAdmin);
//
//        //Директор
//        $director  = $auth->createRole('director');
//        $director->description = 'Директор';
//        $auth->add($director);
//        //Назначим разрешения для роли
//        $auth->addChild($director, $manager);
//
//        //Администратор
//        $admin  = $auth->createRole('admin');
//        $admin->description = 'Администратор';
//        $auth->add($admin);
//        //Назначим разрешения для роли
//        $auth->addChild($admin, $director);
//
//        // Назначение ролей пользователям. 1 и 2 это IDs возвращаемые IdentityInterface::getId()
//        // обычно реализуемый в модели User.
//        $auth->assign($admin, 1);
    }
}