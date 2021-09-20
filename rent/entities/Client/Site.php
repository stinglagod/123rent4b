<?php

namespace rent\entities\Client;

use rent\entities\behaviors\MetaBehavior;
use rent\entities\Client\Site\Counter;
use rent\entities\Client\Site\Footer;
use rent\entities\Client\Client;
use rent\entities\Client\Site\MainPage;
use rent\entities\Client\Site\ReCaptcha;
use rent\entities\Page;
use rent\entities\Shop\Category;
use rent\entities\Social;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use rent\entities\Meta;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%client_sites}}".
 *
 * @property int $id
 * @property int $client_id
 * @property int $created_at
 * @property int $updated_at
 * @property string $name
 * @property int $status
 * @property string $telephone
 * @property string $address
 * @property string $domain
 * @property string $email
 * @property string $urlInstagram
 * @property string $urlTwitter
 * @property string $urlFacebook
 * @property string $urlGooglePlus
 * @property string $urlVk
 * @property string $urlOk
 * @property string $timezone
 * @property int $logo_id
 * @property string mainPage_json
 * @property string footer_json
 * @property string counter_json
 * @property string reCaptcha_json
 * @property int is_https
 * @property string protocol
 *
 * @property Client $client
 * @property File $logo
 * @property Category[] $categories
 * @property Page[] $pages
 * @property MainPage $mainPage
 * @property Footer $footer
 * @property Counter $counter
 * @property ReCaptcha $reCaptcha
 * @property Meta $meta
 * @property Social $social
 */
class Site extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const DEFAULT_LOGO_PATH = '/web/images/logo/logo.png';

    public $mainPage;
    public $footer;
    public $counter;
    public $reCaptcha;
    public $meta;
    public $social;

    public static function create($name, $domain, $telephone, $address, $timezone,Meta $meta=null): self
    {
        $site = new static();
        $site->name = $name;
        $site->domain = $domain;
        $site->telephone = $telephone;
        $site->address = $address;
        $site->timezone = $timezone;
        $site->meta = $meta?:new Meta('','','');

        // добавляем корень категории
        if (empty(Category::getRoot())) {
            $categories=$site->categories;
            $category=Category::createRoot();
            $category->makeRoot();
            $categories[]=$category;
            $site->categories=$categories;
        }

        // добавляем корень page
        $pages = $site->pages;
        $page = Page::createRoot();
        $page->makeRoot();
        $pages[] = $page;
        $site->pages = $pages;

        return $site;
    }

    public function edit(
        $name,
        $isHttps,
        $domain,
        $telephone,
        $address,
        $email,
        Social $social,
        $timezone,
        MainPage $mainPage,
        Footer $footer,
        Counter $counter,
        ReCaptcha $reCaptcha,
        Meta $meta
    ): void
    {

        $this->updated_at=0;

        $this->name = $name;
        $this->is_https = $isHttps;
        $this->domain = $domain;
        $this->telephone = $telephone;
        $this->address = $address;
        $this->email = $email;
        $this->urlInstagram = $social->urlInstagram;
        $this->urlTwitter = $social->urlTwitter;
        $this->urlFacebook = $social->urlFacebook;
        $this->urlGooglePlus = $social->urlGooglePlus;
        $this->urlVk = $social->urlVk;
        $this->urlOk = $social->urlOk;
        $this->timezone = $timezone;
        $this->mainPage->set($mainPage);
        $this->footer->set($footer);
        $this->counter->set($counter);
        $this->reCaptcha->set($reCaptcha);
        $this->meta = $meta;
    }

    public function addLogo($file): void
    {
        $this->updated_at=null;
        $this->logo=File::create($file);

    }

    public function isIdEqualTo($id)
    {
        return $this->id == $id;
    }

    public function isHttps()
    {
        return $this->is_https;
    }

    public function getProtocol():string
    {
        return $this->is_https?'https':'http';
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%client_sites}}';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            MetaBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['categories','logo','pages'],
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }

    public static function findByDomain($domain)
    {
        return static::findOne(['domain' => $domain]);
    }

    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['site_id' => 'id']);
    }
    public function getPages(): ActiveQuery
    {
        return $this->hasMany(Page::class, ['site_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogo()
    {
        return $this->hasOne(File::class, ['id' => 'logo_id']);
    }

    public function beforeDelete(): bool
    {
        if (parent::beforeDelete()) {
            return true;
        }
        return false;
    }
    public function afterSave($insert, $changedAttributes)
    {

        //Если меняется лого, тогда надо удалить старое лого и добавить лого в быструю загрузку /uploads/sites/SITE_ID/logo.png
        if (key_exists('logo_id',$changedAttributes)) {
            if ($this->oldLogo_id) {
                File::findOne($this->oldLogo_id)->delete();
            }
            $this->addLogoToFrontend($this->logo->getImageFileUrl('file'));

        }
        parent::afterSave($insert, $changedAttributes);

    }
    public function afterFind()
    {
        $this->social=new Social(
            $this->urlInstagram,
            $this->urlTwitter,
            $this->urlFacebook,
            $this->urlGooglePlus,
            $this->urlVk,
            $this->urlOk
        );
        $this->mainPage=new MainPage($this->mainPage_json);
        $this->footer=new Footer($this->footer_json);
        $this->counter=new Counter($this->counter_json);
        $this->reCaptcha=new ReCaptcha($this->reCaptcha_json);
        parent::afterFind();
    }

    private $oldLogo_id;
    public function beforeSave($insert)
    {
        //Если меняется лого, тогда надо удалить старое лого и добавить лого в быструю загрузку /uploads/sites/SITE_ID/logo.png
        if ($oldLogo_id=$this->getOldAttribute('logo_id')) {
            if ($oldLogo_id!=$this->getAttribute('logo_id')) {
                $this->oldLogo_id=$oldLogo_id;
            }
        }
        //mainPage to json
        $this->mainPage_json=$this->mainPage?$this->mainPage->getJson():'';
        $this->footer_json=$this->footer?$this->footer->getJson():'';
        $this->counter_json=$this->counter?$this->counter->getJson():'';
        $this->reCaptcha_json=$this->reCaptcha?$this->reCaptcha->getJson():'';


        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
    private function getLogoDir()
    {
        return Yii::getAlias('@frontend').'/web/uploads/sites/'.$this->id.'/';
    }
    public function getLogoPath()
    {
        return $this->getLogoDir().'logo.png';
    }
    public function addLogoToFrontend(string $path=null):void
    {
        $path=empty($path)?Yii::getAlias('@frontend').self::DEFAULT_LOGO_PATH:$path;
        if (!file_exists($this->getLogoDir())) {
            mkdir($this->getLogoDir(),0750,true);
        }
//            copy($path, $this->getLogoPath());
        copy($path, $this->getLogoPath());
    }

    public static function find($all=false)
    {
        if ($all) {
            return parent::find();
        } else {
            return parent::find()->where(['client_id' => Yii::$app->settings->client->id]);
        }
    }
}
