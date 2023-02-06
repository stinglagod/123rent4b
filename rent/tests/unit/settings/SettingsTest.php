<?php

namespace rent\tests\unit\settings;


use Codeception\Test\Unit;
use common\fixtures\ClientFixture;
use common\fixtures\SiteFixture;
use common\fixtures\UserAssignmentFixture;
use common\fixtures\UserFixture;
use rent\cart\Cart;
use rent\entities\Client\Client;
use rent\entities\Client\Site;
use rent\entities\User\User;
use rent\repositories\Client\ClientRepository;
use rent\repositories\Client\SiteRepository;
use rent\repositories\UserRepository;
use rent\settings\Settings;
use rent\settings\storage\SimpleStorage;
use rent\settings\storage\StorageInterface;
use rent\tests\UnitTester;
use Yii;
use yii\caching\CacheInterface;

class SettingsTest extends Unit
{
    protected UnitTester $tester;

    private CacheInterface $cache;
    private SiteRepository $repo_sites;
    private UserRepository $repo_users;
    private ClientRepository $repo_clients;
    private ?Cart $cart;
    private ?StorageInterface $storage;

    private Site $mainSite;
    private Client $mainClient;

    private Site $clientSite;
    private Client $client;
    private Site $clientSite2;
    private Client $client2;

    private User $superAdmin;
    private User $admin;
    private User $manager;
    private User $user;

    public function _before()
    {
        parent::_before();

        $this->cache= Yii::$app->cache;
        $this->repo_sites= new SiteRepository();
        $this->repo_users= new UserRepository();
        $this->repo_clients= new ClientRepository();
        $this->cart= null;
        $this->storage= null;

        $this->tester->haveFixtures([
            'client' => [
                'class' => ClientFixture::class,
                'dataFile' => codecept_data_dir() . 'client.php'
            ],
            'site' => [
                'class' => SiteFixture::class,
                'dataFile' => codecept_data_dir() . 'site.php'
            ],
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
            'userAssignment' => [
                'class' => UserAssignmentFixture::class,
                'dataFile' => codecept_data_dir() . 'userAssignment.php'
            ]
        ]);
        //Таким образом добавляем роли
        Yii::$app->db->createCommand()->truncateTable('auth_assignments')->execute();
        Yii::$app->db->createCommand()->insert('auth_assignments', [
            'item_name'=>'super_admin',
            'user_id'=>1003,
            'created_at'=>time(),
        ])->execute();
        Yii::$app->db->createCommand()->insert('auth_assignments', [
            'item_name'=>'admin',
            'user_id'=>1004,
            'created_at'=>time(),
        ])->execute();
        Yii::$app->db->createCommand()->insert('auth_assignments', [
            'item_name'=>'manager',
            'user_id'=>1005,
            'created_at'=>time(),
        ])->execute();
        Yii::$app->db->createCommand()->insert('auth_assignments', [
            'item_name'=>'user',
            'user_id'=>1006,
            'created_at'=>time(),
        ])->execute();

        //MainSite
        $this->mainSite=$this->repo_sites->findByDomainOrId(Yii::$app->params['mainSiteId']);
        $this->mainClient=$this->repo_clients->get(Yii::$app->params['mainClientId']);

        //Client`s Site
        $this->clientSite=$this->repo_sites->findByDomainOrId(1000);
        $this->client=$this->repo_clients->get(1000);

        $this->clientSite2=$this->repo_sites->findByDomainOrId(2000);
        $this->client2=$this->repo_clients->get(2000);



        //superAdmin
        $this->superAdmin=$this->repo_users->get(1003);
        $this->admin=$this->repo_users->get(1004);
        $this->manager=$this->repo_users->get(1005);
        $this->user=$this->repo_users->get(1006);

    }



########################################################################################################################
#guest
    #Main Site
    /**
     * Открываем основной домен(rent4b.ru). Незарегистрированным пользователем
     * По настройкам должны получить:
     * Сайт: rent4b.ru
     * Клиент: rent4b.ru
     */
    public function testOpenMainSiteFrontendGuestSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->mainSite->domain;

        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage());

        $this->assertEquals($this->mainSite->id, $settings->site->id);
        $this->assertEquals($this->mainSite->client->id, $settings->client->id);
    }
    /**
     * Открываем админку основного домена(rent4b.ru). Зарегистрированным пользователем
     * По настройкам должны получить:
     * Сайт: null
     * Клиент: null
     */
    public function testOpenMainSiteBackendGuestSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->mainSite->domain;

        $settings = $this->createStub(Settings::class);
        $settings->method('isBackend')->willReturn(true);

        $this->assertEquals(null, $settings->site);
        $this->assertEquals(null, $settings->client);

    }
    #Client`s Site
    /**
     * Открываем сайт клиента(gazprom.ru). Незарегистрированным пользователем
     * По настройкам должны получить:
     * Сайт: gazprom.ru (1000)
     * Клиент: ПАО Газпром (1000)
     */
    public function testOpenClientsSiteFrontendGuestSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->clientSite->domain;

        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage());

        $siteId=$settings->site?$settings->site->id:null;
        $clientId=$settings->client?$settings->client->id:null;

        $this->assertEquals($this->clientSite->id, $siteId);
        $this->assertEquals($this->clientSite->client->id, $clientId);
    }
    /**
     * Открываем админку сайт клиента(gazprom.ru). Незарегистрированным пользователем
     * По настройкам должны получить:
     * Сайт: null
     * Клиент: null
     */
    public function testOpenClientsSiteBackendGuestSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->clientSite->domain;

        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage(),true);

        $siteId=$settings->site?$settings->site->id:null;
        $clientId=$settings->client?$settings->client->id:null;

        $this->assertEquals(null, $siteId);
        $this->assertEquals(null, $clientId);

    }
########################################################################################################################
#user
    #Main Site
    /**
     * Открываем основной домен(rent4b.ru). Зарегистрированным пользователем
     * По настройкам должны получить:
     * Сайт: rent4b.ru
     * Клиент: rent4b.ru
     */
    public function testOpenMainSiteFrontendRegUserSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->mainSite->domain;
        Yii::$app->user->setIdentity($this->user);

        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage());

        $this->assertEquals($this->mainSite->id, $settings->site->id);
        $this->assertEquals($this->mainSite->client->id, $settings->client->id);
    }
    /**
     * Открываем админку основного домена(rent4b.ru). Зарегистрированным пользователем
     * По настройкам должны получить:
     * Сайт: null
     * Клиент: null
     */
    public function testOpenMainSiteBackendRegUserSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->mainSite->domain;
        Yii::$app->user->setIdentity($this->user);
        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage(),true);

        $this->assertEquals(null, $settings->site);
        $this->assertEquals(null, $settings->client);

    }
    #Client`s Site
    /**
     * Открываем сайт клиента(gazprom.ru). Зарегистрированным пользователем
     * По настройкам должны получить:
     * Сайт: gazprom.ru (1000)
     * Клиент: ПАО Газпром (1000)
     */
    public function testOpenClientsSiteFrontendRegUserSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->clientSite->domain;
        Yii::$app->user->setIdentity($this->user);

        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage());

        $siteId=$settings->site?$settings->site->id:null;
        $clientId=$settings->client?$settings->client->id:null;

        $this->assertEquals($this->clientSite->id, $siteId);
        $this->assertEquals($this->clientSite->client->id, $clientId);
    }
    /**
     * Открываем админку сайт клиента(gazprom.ru). Зарегистрированным пользователем
     * По настройкам должны получить:
     * Сайт: null
     * Клиент: null
     */
    public function testOpenClientsSiteBackendRegUserSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->clientSite->domain;
        Yii::$app->user->setIdentity($this->user);
        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage(),true);

        $siteId=$settings->site?$settings->site->id:null;
        $clientId=$settings->client?$settings->client->id:null;

        $this->assertEquals(null, $siteId);
        $this->assertEquals(null, $clientId);
    }
########################################################################################################################
#manager,admin
    #Main Site
    /**
     * Открываем основной домен(rent4b.ru). Пользователи с ролями manager,admin
     * По настройкам должны получить:
     * Сайт: rent4b.ru
     * Клиент: rent4b.ru
     */
    public function testOpenMainSiteFrontendManagerAndAdminSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->mainSite->domain;
        Yii::$app->user->setIdentity($this->manager);

        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage());

        $this->assertEquals($this->mainSite->id, $settings->site->id);
        $this->assertEquals($this->mainSite->client->id, $settings->client->id);
    }

    /**
     * Открываем админку основного домена(rent4b.ru). Пользователи с ролью manager
     * По настройкам должны получить:
     * Сайт: Сайт по умолчанию пользователя
     * Клиент: Клиент по умолчанию
     */
    public function testOpenMainSiteBackendManagerSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->mainSite->domain;
        Yii::$app->user->setIdentity($this->manager);
        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage(),true);

        $siteId=$settings->site?$settings->site->id:null;
        $clientId=$settings->client?$settings->client->id:null;

        $this->assertEquals($this->user->default_site, $siteId);
        $this->assertEquals($this->user->default_client_id, $clientId);
    }

    /**
     * Открываем админку основного домена(rent4b.ru). Пользователи с ролями admin
     * По настройкам должны получить:
     * Сайт: Сайт по умолчанию пользователя
     * Клиент: Клиент по умолчанию
     */
    public function testOpenMainSiteBackendAdminSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->mainSite->domain;
        Yii::$app->user->setIdentity($this->admin);
        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage(),true);

        $siteId=$settings->site?$settings->site->id:null;
        $clientId=$settings->client?$settings->client->id:null;

        dump($this->user->default_site);
        dump($siteId);
        exit;

        $this->assertEquals($this->user->default_site, $siteId);
        $this->assertEquals($this->user->default_client_id, $clientId);

    }
    #Client`s Site
    /**
     * Открываем сайт клиента(gazprom.ru). Пользователи с ролями manager,admin
     * По настройкам должны получить:
     * Сайт: gazprom.ru (1000)
     * Клиент: ПАО Газпром (1000)
     */
    public function testOpenClientsSiteFrontendManagerAndAdminSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->clientSite->domain;
        Yii::$app->user->setIdentity($this->manager);

        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage());

        $siteId=$settings->site?$settings->site->id:null;
        $clientId=$settings->client?$settings->client->id:null;

        $this->assertEquals($this->user->default_site, $siteId);
        $this->assertEquals($this->user->default_client_id, $clientId);
    }


    /**
     * Открываем админку сайт клиента(gazprom.ru). Пользователи с ролями manager,admin
     * По настройкам должны получить:
     * Сайт: gazprom.ru (1000)
     * Клиент: ПАО Газпром (1000)
     */
    public function testOpenClientsSiteBackendManagerAndAdminSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->clientSite->domain;
        Yii::$app->user->setIdentity($this->manager);

        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage(),true);

        $siteId=$settings->site?$settings->site->id:null;
        $clientId=$settings->client?$settings->client->id:null;

        $this->assertEquals($this->user->default_site, $siteId);
        $this->assertEquals($this->user->default_client_id, $clientId);
    }
    /**
     * Открываем админку сайт чужого клиента(roga.ru). Пользователи с ролями manager,admin
     * По настройкам должны получить:
     * Сайт: null
     * Клиент: null
     */
    public function testOpenAlienClientsSiteBackendManagerAndAdminSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->clientSite2->domain;
        Yii::$app->user->setIdentity($this->manager);

        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage(),true);

        $siteId=$settings->site?$settings->site->id:null;
        $clientId=$settings->client?$settings->client->id:null;

        $this->assertEquals(null, $siteId);
        $this->assertEquals(null, $clientId);
    }
########################################################################################################################
#superAdmin
    #Main Site
    /**
     * Открываем основной домен(rent4b.ru). Пользователи с ролями superAdmin
     * По настройкам должны получить:
     * Сайт: rent4b.ru
     * Клиент: rent4b.ru
     */
    public function testOpenMainSiteFrontendSuperAdminSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->mainSite->domain;
        Yii::$app->user->setIdentity($this->superAdmin);

        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage());

        $siteId=$settings->site?$settings->site->id:null;
        $clientId=$settings->client?$settings->client->id:null;

        $this->assertEquals($this->mainSite->id, $siteId);
        $this->assertEquals($this->mainClient->id, $clientId);
    }
    /**
     * Открываем админку основного домена(rent4b.ru). Пользователи с ролями superAdmin
     * По настройкам должны получить:
     * Сайт: Сайт по умолчанию клиента
     * Клиент: Выбранный клиент
     */
    public function testOpenMainSiteBackendSuperAdminSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->mainSite->domain;
        Yii::$app->user->setIdentity($this->superAdmin);

        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage(),true);

        $siteId=$settings->site?$settings->site->id:null;
        $clientId=$settings->client?$settings->client->id:null;

        $this->assertEquals($this->mainSite->id, $siteId);
        $this->assertEquals($this->mainClient->id, $clientId);

    }

    /**
     * Открываем админку основного домена(rent4b.ru). Пользователи с ролями superAdmin. Выбираем в настройках клиента
     * По настройкам должны получить:
     * Сайт: Сайт клиента по умолчанию
     * Клиент: Установленный клиент
     */
    public function testOpenMainSiteBackendSuperAdminAnyClientSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->mainSite->domain;
        Yii::$app->user->setIdentity($this->superAdmin);

        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage($this->client->id,$this->clientSite->id),true);
        $settings->initClient($this->client->id);

        $siteId=$settings->site?$settings->site->id:null;
        $clientId=$settings->client?$settings->client->id:null;

        $this->assertEquals($this->client->id, $clientId);
        $this->assertEquals($this->clientSite->id, $siteId);
    }
    #Client`s Site
    /**
     * Открываем сайт клиента(gazprom.ru). Пользователи с ролями superAdmin
     * По настройкам должны получить:
     * Сайт: gazprom.ru (1000)
     * Клиент: ПАО Газпром (1000)
     */
    public function testOpenClientsSiteFrontendSuperAdminSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->clientSite->domain;
        Yii::$app->user->setIdentity($this->superAdmin);

        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage());

        $siteId=$settings->site?$settings->site->id:null;
        $clientId=$settings->client?$settings->client->id:null;

        $this->assertEquals($this->user->default_site, $siteId);
        $this->assertEquals($this->user->default_client_id, $clientId);
    }
    /**
     * Открываем админку сайт клиента(gazprom.ru). Пользователи с ролями superAdmin
     * По настройкам должны получить:
     * Сайт: gazprom.ru (1000)
     * Клиент: ПАО Газпром (1000)
     */
    public function testOpenClientsSiteBackendSuperAdminSuccess()
    {
        $_SERVER['HTTP_HOST']=$this->clientSite->domain;
        Yii::$app->user->setIdentity($this->superAdmin);

        $settings=new Settings($this->cache, $this->repo_sites, $this->repo_users, $this->repo_clients,null, new SimpleStorage());

        $siteId=$settings->site?$settings->site->id:null;
        $clientId=$settings->client?$settings->client->id:null;

        $this->assertEquals($this->user->default_site, $siteId);
        $this->assertEquals($this->user->default_client_id, $clientId);
    }
}