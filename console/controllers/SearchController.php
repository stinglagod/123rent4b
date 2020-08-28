<?php

namespace console\controllers;

use rent\entities\Shop\Product\Product;
use rent\services\search\ProductIndexer;
use yii\console\Controller;
use Elasticsearch\Client;

class SearchController extends Controller
{
    private $indexer;
    private $client;

    public function __construct($id, $module, Client $client, ProductIndexer $indexer, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->indexer = $indexer;
        $this->client = $client;
    }

    public function actionReindex($client_id=null): void
    {
        if (empty($client_id)) {
            $clients=\rent\entities\Client\Client::find()->all();
            foreach ($clients as $client) {
                $this->actionReindex($client);
            }
        }
        if (!$client=\rent\entities\Client\Client::findOne($client_id)) return;
        if (!$site_id=$client->getFirstSite()->id) return;
        $this->stdout('======Client: '.$client->name . PHP_EOL);
        foreach ($client->sites as $site) {
            $this->stdout('---SITE: '.$site->domain . PHP_EOL);
            \Yii::$app->params['siteId']=$site->id;

            $query = Product::find()
                ->active()
                ->with(['category', 'categoryAssignments', 'tagAssignments', 'values'])
                ->orderBy('id');

            $this->stdout('Clearing' . PHP_EOL);

            $this->indexer->clear();

            $this->stdout('Indexing of products' . PHP_EOL);

            foreach ($query->each() as $product) {
                /** @var Product $product */
                $this->stdout('Product #' . $product->id . PHP_EOL);
                $this->indexer->index($product);
            }

            $this->stdout('Done!' . PHP_EOL);
        }

    }
}