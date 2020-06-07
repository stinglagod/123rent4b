<?php

namespace rent\services\manage\Client;

use rent\entities\Client\Client;
use rent\entities\User\User;
use rent\forms\manage\Client\ClientCreateForm;
use rent\forms\manage\Client\ClientEditForm;
use rent\repositories\Client\ClientRepository;

class ClientManageService
{
    private $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(ClientCreateForm $form): User
    {
        $client = Client::create(
            $form->name,
            $form->status
        );
        $this->repository->save($client);
        return $client;
    }

    public function edit($id, ClientEditForm $form): void
    {
        $user = $this->repository->get($id);
        $user->edit(
            $form->name,
            $form->status
        );
        $this->repository->save($user);
    }

    public function remove($id): void
    {
        $user = $this->repository->get($id);
        $this->repository->remove($user);
    }
}