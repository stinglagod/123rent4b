<?php

namespace rent\useCases\manage\Client;

use backend\bootstrap\Settings;
use rent\entities\Client\Client;
use rent\entities\Client\Site;
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

class SiteManageService
{
    private $site;

    public function __construct(
        SiteRepository $site
    )
    {
        $this->site = $site;

    }

    public function moveMainSliderUp (Site $site, $key)
    {
        $site->mainPage->mainSliderUp($key);
        $this->site->save($site);
    }
    public function moveMainSliderDown (Site $site, $key)
    {
        $site->mainPage->mainSliderDown($key);
        $this->site->save($site);
    }
    public function removeMainSlider (Site $site, $key)
    {
        $site->mainPage->removeMainSlider($key);
        $this->site->save($site);
    }

}