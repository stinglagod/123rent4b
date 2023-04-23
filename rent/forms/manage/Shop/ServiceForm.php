<?php

namespace rent\forms\manage\Shop;

use rent\entities\Shop\Brand;
use rent\entities\Shop\Service;
use rent\forms\CompositeForm;
use rent\forms\manage\MetaForm;
use rent\validators\SlugValidator;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * @property MetaForm $meta;
 */
class ServiceForm extends Model
{
    public ?string $name=null;
    public ?string $percent=null;
    public ?string $defaultCost=null;
    public ?string $is_depend=null;
    public ?string $status=null;

    private ?Service $_service=null;

    public function __construct(Service $service = null, $config = [])
    {
        if ($service) {
            $this->name = $service->name;
            $this->percent = $service->percent;
            $this->defaultCost = $service->defaultCost;
            $this->is_depend = $service->is_depend;
            $this->status = $service->status;
            $this->_service = $service;
        } else {
            $this->status=Service::STATUS_ACTIVE;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['percent'], 'double', 'min' => 0],
            [['defaultCost'], 'double', 'min' => 0],
            [['is_depend'], 'boolean'],
            ['status', 'default', 'value' => Service::STATUS_ACTIVE],
            ['status', 'in', 'range' => [
                Service::STATUS_ACTIVE,
                Service::STATUS_DELETED,
                Service::STATUS_NOT_ACTIVE
            ]],
        ];
    }
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            [],
            Service::getAttributeLabels()
        );

    }
}