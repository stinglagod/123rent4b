<?php

namespace frontend\models;

use common\models\Category;
use common\models\ProductCategory;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;

/**
 * ProductSearch represents the model behind the search form of `\common\models\Product`.
 */
class ProductSearch extends Product
{
    public $category_id;
    public $alias;
    public $withoutfolder;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'client_id','category_id','withoutfolder'], 'integer'],
            [['name', 'description', 'tag', 'cod', 'is_active', 'productType','alias'], 'safe'],
            [['primeCost', 'priceRent', 'priceSale', 'pricePrime','alias'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Product::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder'=> [
                    'name' => SORT_ASC
                ]
            ]
        ]);
        $query->andWhere(['product.on_site'=>1]);
        if (!isset($params['withoutfolder'])) {
            if (isset($params['alias'])) {
                $catetory=Category::findCategory($params['alias']);
            } else if (isset($params['category_id'])) {
                $catetory=Category::findCategory($params['category_id']);
            }
            if (!empty($catetory)) {
                $query->joinWith('categories')->andFilterWhere(['category.id'=>$catetory->id]);
//                print_r($catetory->id);exit;
//                $productCategories=ProductCategory::find()->select(['product_id'])->where(['category_id' => $catetory->id ])->orderBy('product_id')->asArray()->column();
//                $productCategories=$productCategories?$productCategories:-1;
//                $query->andFilterWhere(['in', 'id', $productCategories]);
            }
        }


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }




        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'primeCost' => $this->primeCost,
            'client_id' => $this->client_id,
            'priceRent' => $this->priceRent,
            'priceSale' => $this->priceSale,
            'pricePrime' => $this->pricePrime,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'tag', $this->tag])
            ->andFilterWhere(['like', 'cod', $this->cod])
            ->andFilterWhere(['like', 'is_active', $this->is_active])
            ->andFilterWhere(['like', 'productType', $this->productType]);
//        return var_dump($query);
//        print_r($dataProvider);exit;
        return $dataProvider;
    }
}

