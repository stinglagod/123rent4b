<?php

namespace rent\entities\Client;

use common\models\File;
use common\models\Movement;
use common\models\Order;
use common\models\Ostatok;
use common\models\Product;
use rent\entities\Client\Site\Counter;
use rent\entities\Client\Site\Footer;
use rent\entities\Client\Site\MainPage;
use rent\entities\Client\Site\ReCaptcha;
use rent\entities\Meta;
use rent\entities\Shop\Category\Category;
use rent\entities\Social;
use rent\entities\User\User;
use rent\helpers\TextHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%clients}}".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $create_at
 * @property int $updated_at
 * @property string $timezone
 *
 * @property User $user
 * @property UserAssignment[] $userAssignments
 * @property User[] $users
 * @property Site[] $sites
 * @property Site $firstSite
 * @property Site $defaultSite
 */
class Client extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const MAIN_CLIENT_ID = 1;

    public static function create(string $name,int $status): self
    {
        $client = new Client();
        $client->name = $name;
        $client->status = $status;
        return $client;
    }

    public function edit(string $name,int $status, string $timezone): void
    {
        $this->name = $name;
        $this->status = $status;
        $this->timezone = $timezone;
    }

    // User
    public function assignUser($id,$isOwner=false): void
    {
        $assignments = $this->userAssignments;
        if (count($assignments) >= Yii::$app->params['numbUsersOfClient'])
            throw new \DomainException('Достигнут лимит по количеству пользователей');

        foreach ($assignments as $assignment) {
            if ($assignment->isForUser($id)) {
                return;
            }
        }
        $assignments[] = UserAssignment::create($id,$isOwner);
        $this->userAssignments = $assignments;
    }

    public function revokeUser($id): void
    {
        $assignments = $this->userAssignments;
        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForUser($id)) {
                if ($assignment->owner)
                    throw new \DomainException('Нельзя удалить владельца');

                unset($assignments[$i]);
                $this->userAssignments = $assignments;
                return;
            }
        }
        throw new \DomainException('Assignment is not found.');
    }
    public function revokeUsers(): void
    {
        $this->userAssignments = [];
    }
    public function makeOwnerUser($id): void
    {
        $assignments = $this->userAssignments;
        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForUser($id)) {
                $assignment->owner=true;
            } else {
                if ($assignment->owner)
                    $assignment->owner=false;
            }
        }
        $this->userAssignments=$assignments;
    }

    public function isIdEqualTo($id)
    {
        return $this->id == $id;
    }

### Sites
    public function getSite($id): Site
    {
        foreach ($this->sites as $site) {
            if ($site->isIdEqualTo($id)) {
                return $site;
            }
        }
        throw new \DomainException('Сайт не найден.');
    }

    public function addSite($name, $domain, $telephone, $address,$timezone): Site
    {
        if (count($this->sites) >= Yii::$app->params['numbSitesOfClient'])
            throw new \DomainException('Достигнут лимит по количеству сайтов');
        if (Site::findByDomain($domain))
            throw new \DomainException('Сайт с таким доменом уже существует.');

        $sites = $this->sites;
        $timezone=empty($timezone)?Site::DEFAULT_TIMEZONE:$timezone;
        $site= Site::create($name, $domain, $telephone, $address,$timezone);
        $sites[] = $site;
        $this->sites = $sites;
        return $site;

    }
    public function editSite($site_id, $name, $isHttps,$domain, $telephone, $address,$email,Social $social,$timezone,MainPage $mainPage,Footer $footer,Counter $counter,ReCaptcha $reCaptcha, Meta $meta): void
    {

        $sites = $this->sites;
        foreach ($sites as $i => $site) {
            if ($site->isIdEqualTo($site_id)) {

                $site->edit($name, $isHttps,$domain, $telephone, $address,$email,$social,$timezone,$mainPage,$footer,$counter,$reCaptcha,$meta);
                $this->sites = $sites;
                return;
            }
        }
        throw new \DomainException('Сайт не найден.');
    }

    public function removeSite($id): void
    {
        $sites = $this->sites;
        foreach ($sites as $i => $site) {
            if ($site->isIdEqualTo($id)) {

                unset($sites[$i]);
                $this->sites = $sites;
                return;
            }
        }
        throw new \DomainException('Сайт не найден.');
    }
    public function getFirstSite(): ?Site
    {
        if ($site=$this->getSites()->orderBy('id')->limit(1)->one()) {
            return $site;
        }
        return null;
    }

    public function getDefaultSite():?Site
    {
        return $this->getFirstSite();
    }
    public function addLogoToSite($site_id, $file): void
    {
        $sites = $this->sites;
        foreach ($sites as $i => $site) {
            if ($site->isIdEqualTo($site_id)) {
                $site->addLogo($file);
                $this->sites = $sites;
                return;
            }
        }
        throw new \DomainException('Сайт не найден.');
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
    public function hasSite(int $siteId):bool
    {
        foreach ($this->sites as $site) {
            if ($site->isIdEqualTo($siteId)) {
                return true;
            }
        }
        return false;
    }

//=========
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%clients}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['userAssignments','sites'],
            ],

        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'status' => 'Статус',
            'timezone'=> 'Часовой пояс',
            'created_at'=>'Добавлена в',
            'domain'=>'Домен',
            'telephone'=>'Телефон',
            'address'=>'Адрес',
        ];
    }


    // User
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getUserAssignments(): ActiveQuery
    {
        return $this->hasMany(UserAssignment::class, ['client_id' => 'id']);
    }

    public function getUsers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->via('userAssignments');
    }

    // Sites
    public function getSites(): ActiveQuery
    {
        return $this->hasMany(Site::class, ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovements()
    {
        return $this->hasMany(Movement::class, ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOstatoks()
    {
        return $this->hasMany(Ostatok::class, ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['client_id' => 'id']);
    }

    public function existsSite($site_id):bool
    {
        return Site::find()->where(['id'=>$site_id,'client_id'=>$this->id])->exists();
    }
}
