<?php

namespace rent\services\manage\Client;

use rent\entities\Client\Client;
use rent\entities\Client\Site;
use rent\entities\User\User;
use rent\forms\manage\Client\ClientCreateForm;
use rent\forms\manage\Client\ClientEditForm;
use rent\forms\manage\Client\SiteForm;
use rent\forms\manage\User\UserCreateForm;
use rent\repositories\Client\ClientRepository;
use rent\services\manage\UserManageService;
use yii\mail\MailerInterface;
use \rent\repositories\UserRepository;

class ClientManageService
{
    private $mailer;
    private $client;
    private $user;

    public function __construct(
        MailerInterface $mailer,
        ClientRepository $client,
        UserRepository $user
    )
    {
        $this->mailer = $mailer;
        $this->client = $client;
        $this->user = $user;

    }

    public function create(ClientCreateForm $form): User
    {
        $client = \rent\entities\Client\Client::create(
            $form->name,
            $form->status
        );
        $this->client->save($client);
        return $client;
    }

    public function edit($id, ClientEditForm $form): void
    {
        $user = $this->client->get($id);
        $user->edit(
            $form->name,
            $form->status
        );
        $this->client->save($user);
    }

    public function remove($id): void
    {
        $user = $this->client->get($id);
        $this->client->remove($user);
    }

    // Users
    public function invite($id,UserCreateForm $form): void
    {
        $client=$this->client->get($id);
        if (!$client->isActive()) {
            throw new \DomainException('Клиент не активен. Пригласить пользователя нельзя.');
        }
//      ищем пользователя
        if (!$user=User::findByEmail($form->email)) {
            //создаем пользователя
            $user=User::create($form->name,$form->email,'');
            //сбрасываем пароль упользователя
            $user->requestPasswordReset();
            $this->user->save($user);
        }
        //Добавляем пользователя к клиенту
        $client->assignUser($user->id);
        $this->client->save($client);

        $sent = $this->mailer
            ->compose(
                ['html' => 'client/user/reset/confirm-html', 'text' => 'client/user/reset/confirm-text'],
                ['user' => $user]
            )
            ->setTo($form->email)
            ->setSubject('К вам пришло приглашения от '.$client->name.' для регистрации на сайте: ' . \Yii::$app->name)
            ->send();
        if (!$sent) {
            throw new \RuntimeException('Email sending error.');
        }
    }
    public function removeUser($id,$user_id): void
    {
        $client=$this->client->get($id);
        if (!$client->isActive()) {
            throw new \DomainException('Клиент не активен. Удалить пользователя нельзя.');
        }
        $client->revokeUser($user_id);
        $this->client->save($client);
    }
    public function makeOwnerUser($id,$user_id): void
    {
        $client=$this->client->get($id);
        if (!$client->isActive()) {
            throw new \DomainException('Клиент не активен. Удалить пользователя нельзя.');
        }

        $client->makeOwnerUser($user_id);
        $this->client->save($client);
    }

    // Sites
    public function addSite($id, SiteForm $form): void
    {
        $client=$this->client->get($id);
        $client->addSite(
            $form->name,
            $form->domain,
            $form->telephone,
            $form->address
        );
        $this->client->save($client);
    }
    public function editSite($id, $site_id, SiteForm $form): void
    {
        $client = $this->client->get($id);
        $client->editSite(
            $site_id,
            $form->name,
            $form->domain,
            $form->telephone,
            $form->address
        );
        $this->client->save($client);
    }
    public function removeSite($id, $site_id): void
    {
        $client = $this->client->get($id);
        $client->removeSite($site_id);
        $this->client->save($client);
    }
}