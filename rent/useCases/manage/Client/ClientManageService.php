<?php

namespace rent\useCases\manage\Client;

use backend\bootstrap\Settings;
use rent\entities\Client\Client;
use rent\entities\Client\Site;
use rent\entities\Meta;
use rent\entities\Social;
use rent\entities\User\User;
use rent\forms\manage\Client\ClientChangeForm;
use rent\forms\manage\Client\ClientCreateForm;
use rent\forms\manage\Client\ClientEditForm;
use rent\forms\manage\Client\Site\SiteForm;
use rent\forms\manage\User\UserCreateForm;
use rent\repositories\Client\ClientRepository;
use rent\repositories\Client\SiteRepository;
use rent\useCases\manage\UserManageService;
use rent\services\search\ProductIndexer;
use yii\mail\MailerInterface;
use \rent\repositories\UserRepository;
use Yii;

class ClientManageService
{
    private $mailer;
    private $client;
    private $user;
    private $indexer;
    private $sites;

    public function __construct(
        MailerInterface $mailer,
        ClientRepository $client,
        UserRepository $user,
        ProductIndexer $indexer,
        SiteRepository $sites
    )
    {
        $this->mailer = $mailer;
        $this->client = $client;
        $this->user = $user;
        $this->indexer = $indexer;
        $this->sites = $sites;

    }

    public function create(ClientCreateForm $form): Client
    {
        $client = Client::create(
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
            $form->status,
            $form->timezone
        );
        $this->client->save($user);
    }

    public function remove($id): void
    {
        $client = $this->client->get($id);
        if (!$client->delete()) {
            throw new \RuntimeException('Removing error.');
        }
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
    public function addSite($id, \rent\forms\manage\Client\Site\SiteForm $form): void
    {
        $client=$this->client->get($id);
        $site=$client->addSite(
            $form->name,
            $form->domain,
            $form->telephone,
            $form->address,
            $form->timezone
        );

        $this->client->save($client);
        $this->indexer->createIndex($site->id);

    }
    public function editSite($id, $site_id, SiteForm $form): void
    {
        $client = $this->client->get($id);
        $client->editSite(
            $site_id,
            $form->name,
            $form->isHttps,
            $form->domain,
            $form->telephone,
            $form->address,
            $form->email,
            new Social(
                $form->urlInstagram,
                $form->urlTwitter,
                $form->urlFacebook,
                $form->urlGooglePlus,
                $form->urlVk,
                $form->urlOk
            ),
            $form->timezone,
            new Site\MainPage(null,$form->mainPage),
            new Site\Footer(null,$form->footer),
            new Site\Counter(null,$form->counter),
            new Site\ReCaptcha(null,$form->reCaptcha),
            new Meta(
                $form->seo->title,
                $form->seo->description,
                $form->seo->keywords
            )
        );
        if ($form->logo->files) {
            $client->addLogoToSite($site_id,$form->logo->files[0]);
        }
        $this->client->save($client);

        $settings=new Settings($client->id,$site_id,$form->timezone);
        $settings->save();
        Yii::$app->cache->flush();
    }
    public function removeSite($id, $site_id): void
    {
        $client = $this->client->get($id);
        $client->removeSite($site_id);
        try {
            $this->indexer->deleteIndex($site_id);
        } catch (\Exception $e) {
        }
        $this->client->save($client);

    }

    public function getSitesArray($client_id=null)
    {
        $sites=Site::find();
        if ($client_id) {
            $sites->where(['client_id'=>$client_id]);
        }
        $sites=$sites->orderBy('domain')->all();
        $out=[];
        foreach ($sites as $site) {
            $out[]=['id'=>$site->id,'name'=>$site->domain];
        }
        return $out;
    }

    public function changeActiveSite($site_id):void
    {
        if ($site_id){
            $site=$this->sites->get($site_id);
            Yii::$app->settings->site=$site;
            Yii::$app->settings->client=$site->client;
        } else {
            Yii::$app->settings->site=null;
        }

        Yii::$app->settings->save();
    }

    public function changeActiveClient($client_id):void
    {
        Yii::$app->settings->client=$this->client->get($client_id);
        Yii::$app->settings->site=null;
        Yii::$app->settings->save();
    }
}