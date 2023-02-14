<?php

namespace rent\useCases\manage\CRM;

use backend\bootstrap\Settings;
use rent\entities\Client\Client;
use rent\entities\Client\Site;
use rent\entities\CRM\Contact;
use rent\entities\Meta;
use rent\entities\Social;
use rent\entities\User\User;
use rent\forms\manage\Client\ClientChangeForm;
use rent\forms\manage\Client\ClientCreateForm;
use rent\forms\manage\Client\ClientEditForm;
use rent\forms\manage\Client\Site\SiteForm;
use rent\forms\manage\CRM\ContactForm;
use rent\forms\manage\User\UserCreateForm;
use rent\forms\manage\User\UserInviteForm;
use rent\helpers\PasswordHelper;
use rent\helpers\SearchHelper;
use rent\helpers\TextHelper;
use rent\repositories\Client\ClientRepository;
use rent\repositories\Client\SiteRepository;
use rent\repositories\CRM\ContactRepository;
use rent\services\RoleManager;
use rent\services\TransactionManager;
use rent\useCases\manage\UserManageService;
use rent\services\search\ProductIndexer;
use yii\mail\MailerInterface;
use \rent\repositories\UserRepository;
use Yii;

class ContactManageService
{
    private ContactRepository $contacts;
    private TransactionManager $transaction;

    public function __construct(
        ContactRepository $contacts,
        TransactionManager $transaction
    )
    {
        $this->contacts = $contacts;
        $this->transaction = $transaction;

    }

    public function create(ContactForm $form): Contact
    {
        $entity = Contact::create(
            $form->name,
            $form->surname,
            $form->patronymic,
            $form->telephone,
            $form->email,
            $form->status,
            $form->note
        );
        $this->contacts->save($entity);

        return $entity;
    }

    public function edit($id, ContactForm $form): void
    {
        $entity = $this->contacts->get($id);
        $entity->edit(
            $form->name,
            $form->surname,
            $form->patronymic,
            $form->telephone,
            $form->email,
            $form->status,
            $form->note
        );
        $this->contacts->save($entity);
    }

    public function remove($id): void
    {
        $entity = $this->contacts->get($id);

        if (!$entity->delete()) {
            throw new \RuntimeException('Removing error.');
        }

    }
}