<?php

namespace common\models;
use common\models\protect\MyActiveRecord;

use Yii;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $tag
 * @property string $cod
 * @property double $primeCost
 * @property double $cost
 * @property string $is_active
 * @property int $client_id
 * @property string $hash
 * @property double $priceRent
 * @property double $priceSale
 * @property double $pricePrime
 * @property string $productType
 *
 * @property Movement[] $movements
 * @property OrderProduct[] $orderProducts
 * @property Ostatok[] $ostatoks
 * @property Client $client
 */
class Product extends MyActiveRecord
{
    const PRODUCT='product';
    const SERVICE='service';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['priceRent', 'priceSale','pricePrime'], 'number'],
            [['client_id'], 'integer'],
            [['is_active'], 'string'],
            [['productType'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 1024],
            [['tag'], 'string', 'max' => 512],
            [['cod'], 'string', 'max' => 20],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Наименование'),
            'description' => Yii::t('app', 'Описание'),
            'tag' => Yii::t('app', 'Теги'),
            'cod' => Yii::t('app', 'код'),
            'pricePrime' => Yii::t('app', 'Себестоимость'),
            'priceSelling' => Yii::t('app', 'Цена продажи'),
            'priceRent' => Yii::t('app', 'Цена аренды'),
            'productType' => Yii::t('app', 'Тип номенклатуры'),
            'is_active' => Yii::t('app', 'Is Active'),
            'client_id' => Yii::t('app', 'Client ID'),
            'categoriesArray' => Yii::t('app', 'Категории'),
            'tagsArray' => Yii::t('app', 'Теги'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovements()
    {
        return $this->hasMany(Movement::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOstatoks()
    {
        return $this->hasMany(Ostatok::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('{{%product_category}}', ['product_id' => 'id']);
    }


    private $_categoriesArray;

    public function getCategoriesArray()
    {
        if ($this->_categoriesArray===null) {
            $this->_categoriesArray = $this->getCategories()->select('id')->column();
        }
        return $this->_categoriesArray;
    }

    public function setCategoriesArray($value)
    {
        return $this->_categoriesArray= (array)$value;
    }

    private $_tagsArray;

    public function getTagsArray()
    {
        if ($this->_tagsArray===null) {
            if ($this->_tagsArray = $this->tag?explode(',',$this->tag):array()) {
//                foreach ($this->_tagsArray as $key => $value) {
//                    $key=$value;
//                }
            }
        }
//        return $this->tag;
//        return ['red', 'green'];
        return $this->_tagsArray;
    }

    public function setTagsArray($value)
    {
        return $this->_tagsArray= (array)$value;
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->updateCategories();

        parent::afterSave($insert, $changedAttributes);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->updateTags();
            return true;
        } else {
            return false;
        }
    }

    private function updateCategories()
    {
        $currentCategoryIds = $this->getCategories()->select('id')->column();
//        print_r($currentCategoryIds); exit;
        $newCategoryIds = $this->getCategoriesArray();
//      тут мы ищем какие категории у нас добавились. вычитаем массив $newCategoryIds из $currentCategoryIds
//      И найденные новые категории добавляем.
        foreach (array_filter(array_diff($newCategoryIds,$currentCategoryIds))as $categoryId) {
            /** @var Category $category*/
            if ($category=Category::findOne($categoryId)) {
                $this->link('categories',$category);
            }
        }

        foreach (array_filter(array_diff($currentCategoryIds,$newCategoryIds))as $categoryId) {
            /** @var Category $category*/
            if ($category=Category::findOne($categoryId)) {
                $this->unlink('categories',$category,true);
            }
        }
    }

    /**
     * Обновляем теги.
     */
    private function updateTags()
    {
        $newTagNames=$this->getTagsArray();
        $currentTagNames=$this->tag?explode($this->tag,','):array();
        $this->tag=implode(',',$newTagNames);
        //TODO: Написать добавление тегов общий справочник.
        foreach (array_filter(array_diff($newTagNames,$currentTagNames))as $tagName) {
            Tag::findOrCreateTag($tagName);
        }
    }


    public function getThumb($size=File::THUMBMIDDLE) {
        /** @var File[] $images*/
        if ($images=$this->getFiles()) {
            return $images[0]->getUrl($size);
        } else {
            return Yii::$app->request->baseUrl.'/200c200/img/nofoto-300x243.png';
        }
    }

    public function getShortDescription() {
        return $this->description?mb_substr($this->description,0,255,'UTF-8').'...':'';
    }

    public function getBalance($date=null) {
        $ostatok=Ostatok::find()->where(['product_id'=>$this->id]);
        if (!(empty($date))) {
            $ostatok->andWhere(['<=','dateTime',$date]);
        };
        $balance=$ostatok->sum('qty');
        return $balance?$balance:0;
    }

    //    TODO: Сделать по изящнее
//https://elisdn.ru/blog/33/generaciia-url-dlia-vlojennih-kategorii-v-yii
    public function getUrl($alias=null)
    {
        $response='/admin/category'.$alias.'/'.$this->id;
        return $response;
    }
}
