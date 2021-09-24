<?php

namespace rent\readModels\Shop;

use Elasticsearch\Client;
use rent\entities\Shop\Category\Category;
use rent\helpers\SearchHelper;
use rent\readModels\Shop\views\CategoryView;
use yii\helpers\ArrayHelper;
use rent\repositories\NotFoundException;

class CategoryReadRepository
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getRoot(): Category
    {
        if (!$root=Category::find()->roots()->one()) {
            throw new NotFoundException('Category root is not found.');
        }
        return $root;
    }

    /**
     * @return Category[]
     */
    public function getAll(): array
    {
        return Category::find()->andWhere(['>', 'depth', 0])->andWhere(['on_site'=>1])->orderBy('lft')->all();
    }

    public function find($id): ?Category
    {
        return Category::find()->andWhere(['id' => $id])->andWhere(['>', 'depth', 0])->one();
    }

    public function findBySlug($slug): ?Category
    {
        return Category::find()->andWhere(['slug' => $slug])->andWhere(['>', 'depth', 0])->one();
    }

    public function findByCode($code): ?Category
    {
        return Category::find()->andWhere(['code' => $code])->andWhere(['>', 'depth', 0])->one();
    }

    public function getTreeWithSubsOf(Category $category = null): array
    {
        $query = Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft');

        if ($category) {
            $criteria = ['or', ['depth' => 1]];
            foreach (ArrayHelper::merge([$category], $category->parents) as $item) {
                $criteria[] = ['and', ['>', 'lft', $item->lft], ['<', 'rgt', $item->rgt], ['depth' => $item->depth + 1]];
            }
            $query->andWhere($criteria);
        }

        $aggs = $this->client->search([
            'index' => SearchHelper::indexName(),
            'type' => 'products',
            'body' => [
                'size' => 0,
                'aggs' => [
                    'group_by_category' => [
                        'terms' => [
                            'field' => 'categories',
                        ]
                    ]
                ],
            ],
        ]);

        $counts = ArrayHelper::map($aggs['aggregations']['group_by_category']['buckets'], 'key', 'doc_count');

        return array_map(function (Category $category) use ($counts) {
                return new CategoryView($category, ArrayHelper::getValue($counts, $category->id, 0));
        }, $query->all());
    }

    public function getTreeWithSubsOf2(Category $category = null)
    {
        return $this->getRoot()->tree($category,true)[0];
    }
}