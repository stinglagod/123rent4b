<?php

namespace rent\forms\manage\Shop;

use rent\entities\Shop\Characteristic;
use rent\helpers\CharacteristicHelper;
use yii\base\Model;
use Yii;

/**
 * @property array $variants
 */
class CharacteristicForm extends Model
{
    public $name;
    public $type;
    public $required;
    public $default;
    public $textVariants;
    public $sort;

    private $_characteristic;

    public function __construct(Characteristic $characteristic = null, $config = [])
    {
        if ($characteristic) {
            $this->name = $characteristic->name;
            $this->type = $characteristic->type;
            $this->required = $characteristic->required;
            $this->default = $characteristic->default;
            $this->textVariants = implode(PHP_EOL, $characteristic->variants);
            $this->sort = $characteristic->sort;
            $this->_characteristic = $characteristic;
        } else {
            $this->sort = Characteristic::find()->max('sort') + 1;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'type', 'sort'], 'required'],
            [['required'], 'boolean'],
            [['default'], 'string', 'max' => 255],
            [['textVariants'], 'string'],
            [['sort'], 'integer'],
            [['name'], 'unique', 'targetClass' => Characteristic::class, 'filter' => $this->_characteristic ? ['<>', 'id', $this->_characteristic->id] : null]
        ];
    }

    public function typesList(): array
    {
        return CharacteristicHelper::typeList();
    }

    public function getVariants(): array
    {
        return preg_split('#\s+#i', $this->textVariants);
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Название'),
            'type' => Yii::t('app', 'Тип характеристики'),
            'sort' => Yii::t('app', 'Количество'),
            'required' => Yii::t('app', 'Обязательно'),
            'required:boolean'=>Yii::t('app','Обязательно'),
            'default' => Yii::t('app', 'Описание '),
            'textVariants'=>Yii::t('app','Комментарий')
        ];
    }
}