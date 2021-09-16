<?php

namespace rent\settings;

use rent\cart\Cart;
use rent\entities\Client\Site;
use rent\entities\User\User;
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

    public $useSaveToSessionCache;

    public $cart;
    private $cache;
    private $repo_sites;
    private $repo_users;

    /**
     * Логика следующая. Если домен общий, тогда мы можем выбирать любые другие сайты
     * Если домен клиента, тогда выбор среди доменов этого клиента
     */
    public function __construct( Cache $cache, SiteRepository $repo_sites, UserRepository $repo_users, Cart $cart=null,$config = [] )
    {
        $this->cache = $cache;
        $this->repo_sites = $repo_sites;
        $this->repo_users = $repo_users;
        $this->cart = $cart;

        if (array_key_exists('useSaveToSessionCache',$config)) {
            $this->useSaveToSessionCache=$config['useSaveToSessionCache'];
        }

        $this->initUser();

        if (($domainOrId=$this->getDomainFromHost())and($domainOrId==\Yii::$app->params['siteDomain'])) {
            $domainOrId=$this->getDomainFromSession();
        }
        if ($this->user) {
            $domainOrId=empty($domainOrId)?$this->user->default_site:$domainOrId;
        }

        $this->initSite($domainOrId);

        parent::__construct($config);
    }

    public function initSite($domainOrId=null)
    {
        $this->site=$this->cache->getOrSet(['settings_site', $domainOrId], function () use ($domainOrId) {
            if (!$site = $this->repo_sites->findByDomainOrId($domainOrId)) {
                $site=$this->repo_sites->get(1);
            }
            return $site;
        }, null, new TagDependency(['tags' => ['sites']]));
        date_default_timezone_set($this->site->timezone);
        \Yii::$app->params['dateControlDisplayTimezone']=date_default_timezone_get();
        $this->save();
    }

    public function initUser()
    {
        if ((empty(\Yii::$app->user))or(\Yii::$app->user->isGuest)) return;

        $this->user=$this->cache->getOrSet(['settings_user', \Yii::$app->user->id], function () {
            return $this->repo_users->get(\Yii::$app->user->id);
        }, null, new TagDependency(['tags' => ['users']]));
    }

    public function load()
    {
        if ($this->useSaveToSessionCache)
            return \Yii::$app->session->get('settings_site_id');
    }
    public function save()
    {
        if ($this->useSaveToSessionCache)
            \Yii::$app->session->set('settings_site_id',$this->site->id);
    }

### Private
    private function getDomainFromSession()
    {
        return self::load();
    }
    private function getDomainFromHost()
    {
        return (array_key_exists('HTTP_HOST',$_SERVER))?$_SERVER['HTTP_HOST']:'';
    }
}