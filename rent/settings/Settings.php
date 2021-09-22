<?php

namespace rent\settings;

use rent\cart\Cart;
use rent\cart\cost\calculator\DynamicCost;
use rent\cart\cost\calculator\SimpleCost;
use rent\cart\storage\HybridStorage;
use rent\entities\Client\Site;
use rent\entities\User\User;
use rent\helpers\AppHelper;
use rent\repositories\Client\ClientRepository;
use rent\repositories\Client\SiteRepository;
use rent\repositories\UserRepository;
use yii\base\Component;
use yii\caching\Cache;
use yii\caching\TagDependency;

/**
 * @property Site $site
 * @property User $user
 **/

class Settings extends Component
{
    public $site;
    public $user;
    public $client;

    public $useSaveToSessionCache;

    public $cart;
    private $cache;
    private $repo_sites;
    private $repo_users;
    private $repo_clients;

    private $client_id;
    private $site_id;


    /**
     * Логика следующая. Если домен общий, тогда мы можем выбирать любые другие сайты
     * Если домен клиента, тогда выбор среди доменов этого клиента
     */
    public function __construct( Cache $cache, SiteRepository $repo_sites, UserRepository $repo_users, ClientRepository $repo_clients, Cart $cart=null,$config = [] )
    {
        $this->cache = $cache;
        $this->repo_sites = $repo_sites;
        $this->repo_users = $repo_users;
        $this->repo_clients = $repo_clients;
        $this->cart = $cart;

        if (\Yii::$app->id!='app-console') {
            $this->load();

            $this->initUser();

            $this->initClient();

            if ($this->site_id)
                $this->initSite();
        }



        parent::__construct($config);
    }

    public function initSite($domainOrId=null)
    {
        if (empty($domainOrId)) {
            if ($this->site_id) {
                $domainOrId=$this->site_id;
            } else {
                return;
            }
        }

        $this->site=$this->cache->getOrSet(['settings_site', $domainOrId], function () use ($domainOrId) {
            if (!$site = $this->repo_sites->findByDomainOrId($domainOrId)) {
                $site=$this->repo_sites->get(1);
            }
            return $site;
        }, null, new TagDependency(['tags' => ['sites']]));
        date_default_timezone_set($this->site->timezone);
        \Yii::$app->params['dateControlDisplayTimezone']=date_default_timezone_get();
    }

    public function initUser(int $userId=null)
    {
        if ($userId) {
            $this->user=$this->cache->getOrSet(['settings_user', $userId], function () use ($userId) {
                return $this->repo_users->get($userId);
            }, null, new TagDependency(['tags' => ['users']]));
        } else {
            if ((empty(\Yii::$app->user))or(\Yii::$app->user->isGuest)) return;

            $this->user=$this->cache->getOrSet(['settings_user', \Yii::$app->user->id], function () {
                return $this->repo_users->get(\Yii::$app->user->id);
            }, null, new TagDependency(['tags' => ['users']]));
        }
    }

    /**
     *  Инициализируем клиента
     *  0. Берутся данныие из сессии.
     *  1. Проверяем домен. Если не общий домен, тогда находим клиент этого сайта
     *  2. Если общий домен. Тогда берем значение у пользователя в Клиент по умолчанию
     */
    public function initClient($clientId=null)
    {
        if ($clientId) {
            $this->client_id=$clientId;
        }


        if ($this->client_id) {
            //0.
            $this->client=$this->cache->getOrSet(['settings_client', $this->client_id], function ()  {
                return $this->repo_clients->get($this->client_id);
            }, null, new TagDependency(['tags' => ['clients']]));
            return;

        } elseif (($domainOrId=$this->getDomainFromHost())and($domainOrId!=\Yii::$app->params['siteDomain'])) {
            //1.
        } else  {
            //2.
            $this->client=$this->cache->getOrSet(['settings_client', $this->user->getDefaultClient()], function ()  {
                return $this->repo_clients->get($this->user->getDefaultClient());
            }, null, new TagDependency(['tags' => ['clients']]));
            return;
        }
        $this->initSite($domainOrId);
        $this->client=$this->site->client;

    }

    public function load()
    {
        if (AppHelper::isConsole()) return;
        $this->client_id=\Yii::$app->session->get('settings_client_id');
        $this->site_id=\Yii::$app->session->get('settings_site_id');
    }
    public function save()
    {
        if (AppHelper::isConsole()) return;
        \Yii::$app->session->set('settings_client_id',$this->client->id);
        if ($this->site) {
            \Yii::$app->session->set('settings_site_id',$this->site->id);
        } else {
            \Yii::$app->session->set('settings_site_id','');
        }

    }

### Private
    private function getDomainFromHost()
    {
        return (array_key_exists('HTTP_HOST',$_SERVER))?$_SERVER['HTTP_HOST']:'';
    }
}