<?php

namespace rent\useCases\manage\Shop;

use Elasticsearch\Client;
use rent\entities\Meta;
use rent\entities\Shop\Product\Movement\Movement;
use rent\entities\Shop\Product\Product;
use rent\entities\Shop\Tag;
use rent\forms\manage\Shop\Product\CategoriesForm;
use rent\forms\manage\Shop\Product\ModificationForm;
use rent\forms\manage\Shop\Product\MovementForm;
use rent\forms\manage\Shop\Product\PhotosForm;
use rent\forms\manage\Shop\Product\PriceForm;
use rent\forms\manage\Shop\Product\ProductCreateForm;
use rent\forms\manage\Shop\Product\ProductEditForm;
use rent\helpers\SearchHelper;
use rent\repositories\Client\SiteRepository;
use rent\repositories\Shop\BrandRepository;
use rent\repositories\Shop\CategoryRepository;
use rent\repositories\Shop\MovementRepository;
use rent\repositories\Shop\ProductRepository;
use rent\repositories\Shop\TagRepository;
use rent\services\search\ProductIndexer;
use rent\services\TransactionManager;
use yii\helpers\ArrayHelper;

class ProductManageService
{
    private $products;
    private $brands;
    private $categories;
    private $tags;
    private $transaction;
    private $movements;
    private $client;
    private $sites;

    public function __construct(
        ProductRepository $products,
        BrandRepository $brands,
        CategoryRepository $categories,
        TagRepository $tags,
        TransactionManager $transaction,
        MovementRepository $movements,
        Client $client,
        SiteRepository $sites,
        ProductIndexer $indexer
    )
    {
        $this->products = $products;
        $this->brands = $brands;
        $this->categories = $categories;
        $this->tags = $tags;
        $this->transaction = $transaction;
        $this->movements = $movements;
        $this->client = $client;
        $this->sites = $sites;
        $this->indexer = $indexer;
    }
    public function create(ProductCreateForm $form): Product
    {
        if ($form->brandId) {
            $brandId = $this->brands->get($form->brandId)->id;
        } else {
            $brandId=null;
        }
        $category = $this->categories->get($form->categories->main);

        $product = Product::create(
            $category->id,
            $form->code,
            $form->name,
            $form->onSite,
            $form->description,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            ),
            $brandId
        );

//        $product->setPrice($form->price->new, $form->price->old);
        $product->priceRent_new=$form->priceRent->new;
        $product->priceRent_old=$form->priceRent->old;

        $product->priceSale_new=$form->priceSale->new;
        $product->priceSale_old=$form->priceSale->old;

        $product->priceCost=$form->priceCost->cost;
        $product->priceCompensation=$form->priceCompensation->cost;

        foreach ($form->categories->others as $otherId) {
            $category = $this->categories->get($otherId);
            $product->assignCategory($category->id);
        }

        foreach ($form->values as $value) {
            $product->setValue($value->id, $value->value);
        }

        foreach ($form->photos->files as $file) {
            $product->addPhoto($file);
        }

        foreach ($form->tags->existing as $tagIdName) {
            if (is_numeric($tagIdName)) {
                $tag=$this->tags->get($tagIdName);
            } else {
                if(!$tag = $this->tags->findByName($tagIdName)) {
                    $tag = Tag::create($tagIdName, $tagIdName);
                    $this->tags->save($tag);
                }
            }
            $product->assignTag($tag->id);
        }

        $this->transaction->wrap(function () use ($product, $form) {
            foreach ($form->tags->newNames as $tagName) {
                if (!$tag = $this->tags->findByName($tagName)) {
                    $tag = Tag::create($tagName, $tagName);
                    $this->tags->save($tag);
                }
                $product->assignTag($tag->id);
            }
            $this->products->save($product);
        });

        $this->indexer->index($product);

        return $product;
    }

    public function edit($id, ProductEditForm $form): void
    {
        $product = $this->products->get($id);

        if ($form->brandId) {
            $brandId = $this->brands->get($form->brandId)->id;
        } else {
            $brandId=null;
        }

        $category = $this->categories->get($form->categories->main);

        $product->edit(
            $brandId,
            $form->code,
            $form->name,
            $form->onSite,
            $form->description,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $product->changeMainCategory($category->id);

        $this->transaction->wrap(function () use ($product, $form) {

            $product->revokeCategories();
            $product->revokeTags();
            $product->revokeSites();

            $this->products->save($product);

//            var_dump($form->priceRent->new);exit;
            $product->priceRent_new=$form->priceRent->new;
            $product->priceRent_old=$form->priceRent->old;

            $product->priceSale_new=$form->priceSale->new;
            $product->priceSale_old=$form->priceSale->old;

            $product->priceCost=$form->priceCost->cost;
            $product->priceCompensation=$form->priceCompensation->cost;

            foreach ($form->categories->others as $otherId) {
                $category = $this->categories->get($otherId);
                $product->assignCategory($category->id);
            }

            foreach ($form->values as $value) {
                $product->setValue($value->id, $value->value);
            }

            foreach ($form->tags->existing as $tagIdName) {
                if (is_numeric($tagIdName)) {
                    $tag=$this->tags->get($tagIdName);
                } else {
                    if(!$tag = $this->tags->findByName($tagIdName)) {
                        $tag = Tag::create($tagIdName, $tagIdName);
                        $this->tags->save($tag);
                    }
                }
                $product->assignTag($tag->id);
            }

            foreach ($form->sites->others as $otherId) {
                $site = $this->sites->get($otherId);
                $product->assignSite($site->id);
            }

            $this->products->save($product);
        });

        $this->indexer->index($product);

    }

    public function changePrice($id, PriceForm $form): void
    {
        $product = $this->products->get($id);
        $product->setPrice($form->new, $form->old);
        $this->products->save($product);
    }

    public function activate($id): void
    {
        $product = $this->products->get($id);
        $product->activate();
        $this->products->save($product);
    }

    public function draft($id): void
    {
        $product = $this->products->get($id);
        $product->draft();
        $this->products->save($product);
    }

    public function addPhotos($id, PhotosForm $form): void
    {
        $product = $this->products->get($id);
        foreach ($form->files as $file) {
            $product->addPhoto($file);
        }
        $this->products->save($product);
    }

    public function movePhotoUp($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->movePhotoUp($photoId);
        $this->products->save($product);
    }

    public function movePhotoDown($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->movePhotoDown($photoId);
        $this->products->save($product);
    }

    public function removePhoto($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->removePhoto($photoId);
        $this->products->save($product);
    }

    public function addRelatedProduct($id, $otherId): void
    {
        $product = $this->products->get($id);
        $other = $this->products->get($otherId);
        $product->assignRelatedProduct($other->id);
        $this->products->save($product);
    }

    public function removeRelatedProduct($id, $otherId): void
    {
        $product = $this->products->get($id);
        $other = $this->products->get($otherId);
        $product->revokeRelatedProduct($other->id);
        $this->products->save($product);
    }

    public function addModification($id, ModificationForm $form): void
    {
        $product = $this->products->get($id);
        $product->addModification(
            $form->code,
            $form->name,
            $form->price
        );
        $this->products->save($product);
    }

    public function editModification($id, $modificationId, ModificationForm $form): void
    {
        $product = $this->products->get($id);
        $product->editModification(
            $modificationId,
            $form->code,
            $form->name,
            $form->price
        );
        $this->products->save($product);
    }

    public function removeModification($id, $modificationId): void
    {
        $product = $this->products->get($id);
        $product->removeModification($modificationId);
        $this->products->save($product);
    }

    public function remove($id): void
    {
        $product = $this->products->get($id);
        $this->products->remove($product);
    }

    public function addMovement($id, MovementForm $form): void
    {
        $product = $this->products->get($id);
        $product->addMovement(
            $form->date_begin,
            (int)$form->date_end,
            $form->qty,
            $product->id,
            $form->type_id,
            true,
            empty($form->depend_id)?null:$form->depend_id,
            $form->comment
        );
        $this->products->save($product);
    }

    public function removeMovement($id, $movementId): void
    {
        $product = $this->products->get($id);
        $product->removeMovement($movementId);
        $this->products->save($product);
    }

    public function correctBalance($id,$begin,$qty):void
    {
        $product = $this->products->get($id);
        //1. Деактивируем все движения младше $begin
        if ($movements=Movement::find()->andWhere(['<','date_begin',$begin])->all()) {
            /** @var Movement $movement */
            foreach ($movements as $movement) {
                if (($movement->date_end) and ($movement->date_end>$begin)) {
                    throw new \DomainException('There are movements with an end date later than the correct date');
                }
                $movement->deactive();
                $this->movements->save($movement);
            }
        }
        //2. Добавляем движение приход на дату $begin
        $product->addMovement($begin,null,$qty,$product->id,Movement::TYPE_CORRECT,true);
        $this->products->save($product);
    }
    private function updateCategoriesOnSiteStatus(int $isOnSite,Product $product)
    {
        //собираем количество товаров в категории
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
                'query' => [
                    'bool' => [
                        'must' => array_merge(
                            array_filter([
                                ['match' => ['on_site' => 1]],
                            ])
                        )
                    ]
                ]
            ],
        ]);
        $counts = ArrayHelper::map($aggs['aggregations']['group_by_category']['buckets'], 'key', 'doc_count');
        foreach ($product->getAllCategories() as $category) {
            if (array_key_exists($category->id,$counts)){
                if ($isOnSite) {
                    $counts[$category->id]++;
                } else {
                    $counts[$category->id]--;
                    if (($counts[$category->id])<1) {
                        unset($counts[$category->id]);
                    }
                }
            } else {
                if ($isOnSite) {
                    $counts[$category->id]=1;
                }
            }
        }

        $categories = $this->categories->getAll();
        foreach ($categories as $category) {
            if ($category->isRoot()) continue;

            if (array_key_exists($category->id,$counts)) {
                if (!$category->isOnSite()) {
                    $category->onSite();
                }
            } else {
                if ($category->isOnSite()) {
                    $category->offSite();
                }
            }

            $this->categories->save($category);
        }
    }
    public function onSite($id,$on):void
    {
        $product = $this->products->get($id);

        if ($on) {
            $product->onSite();
        } else {
            $product->offSite();
        }
        $this->transaction->wrap(function () use ($product) {
            $this->products->save($product);
            $this->indexer->index($product);
            $this->updateCategoriesOnSiteStatus(1,$product);
        });
    }
}