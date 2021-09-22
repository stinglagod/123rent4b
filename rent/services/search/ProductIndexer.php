<?php

namespace rent\services\search;

use Elasticsearch\Client;
use rent\entities\Client\Site;
use rent\entities\Shop\Category;
use rent\entities\Shop\Product\Product;
use rent\entities\Shop\Product\Value;
use rent\helpers\SearchHelper;
use yii\helpers\ArrayHelper;
use Elasticsearch\Common\Exceptions\Missing404Exception;

class ProductIndexer
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;

    }

    public function clear($indexName): void
    {
        try {
            $this->client->search(['index'=>$indexName]);
        } catch (Missing404Exception $e) {
            $this->createIndex($indexName);
            return;
        }

        $this->client->deleteByQuery([
            'index' => $indexName,
            'type' => 'products',
            'body' => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ]);
    }

    private function _index(Product $product,$indexName): void
    {
        $this->client->index([
            'index' => $indexName,
            'type' => 'products',
            'id' => $product->id,
            'body' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => strip_tags($product->description),
                'priceSale' => $product->priceSale_new,
                'priceRent' => $product->priceRent_new,
                'rating' => $product->rating,
                'brand' => $product->brand_id,
                'on_site' => $product->on_site,
                'categories' => ArrayHelper::merge(
                    [$product->category->id],
                    ArrayHelper::getColumn($product->category->parents, 'id'),
                    ArrayHelper::getColumn($product->categories, 'id'),
                    array_reduce(array_map(function (Category $category) {
                        return ArrayHelper::getColumn($category->parents, 'id');
                    }, $product->categories), 'array_merge', [])
                ),
                'tags' => ArrayHelper::getColumn($product->tagAssignments, 'tag_id'),
                'values' => array_map(function (Value $value) {
                    return [
                        'characteristic' => $value->characteristic_id,
                        'value_string' => (string)$value->value,
                        'value_int' => (int)$value->value,
                    ];
                }, $product->values),
                'sites' => ArrayHelper::getColumn($product->siteAssignments, 'site_id'),
            ],
        ]);
    }

    public function index(Product $product): void
    {
        if ($product->on_site==1) {
            foreach ($product->sites as $site) {
                $this->_index($product,SearchHelper::indexNameFrontend($site->id));
            }
        }
        $this->_index($product,SearchHelper::indexNameBackend());
    }

    public function remove(Product $product): void
    {
        try {
            foreach ($product->sites as $site) {
                $this->client->delete([
                    'index' => SearchHelper::indexNameFrontend($site->id),
                    'type' => 'products',
                    'id' => $product->id,
                ]);
            }
            $this->client->delete([
                'index' =>SearchHelper::indexNameBackend(),
                'type' => 'products',
                'id' => $product->id,
            ]);
        } catch (Missing404Exception $e) {
            return;
        }

    }

    public function reIndex(Product $product):void
    {

        $this->remove($product);
        $this->index($product);
    }

    public function createIndex($name):void
    {
        $this->client->indices()->create([
            'index' => $name,
            'body' => [
                'mappings' => [
                    'products' => [
                        '_source' => [
                            'enabled' => true,
                        ],
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                            ],
                            'description' => [
                                'type' => 'text',
                            ],
                            'price' => [
                                'type' => 'integer',
                            ],
                            'rating' => [
                                'type' => 'float',
                            ],
                            'brand' => [
                                'type' => 'integer',
                            ],
                            'categories' => [
                                'type' => 'integer',
                            ],
                            'tags' => [
                                'type' => 'integer',
                            ],
                            'values' => [
                                'type' => 'nested',
                                'properties' => [
                                    'characteristic' => [
                                        'type' => 'integer'
                                    ],
                                    'value_string' => [
                                        'type' => 'keyword',
                                    ],
                                    'value_int' => [
                                        'type' => 'integer',
                                    ],
                                ]
                            ],
                            'sites' => [
                                'type' => 'integer',
                            ],
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function deleteIndex(string $indexName): void
    {
        $this->client->indices()->delete(['index' => $indexName]);
    }
}