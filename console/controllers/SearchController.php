<?php

namespace console\controllers;

use rent\entities\Shop\Product\Product;
use rent\helpers\SearchHelper;
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
                $this->actionReindex($client->id);
            }
        }

        if (!$client=\rent\entities\Client\Client::findOne($client_id)) return;

        \Yii::$app->settings->initClient($client->id);

        if (!$site_id=$client->getFirstSite()->id) return;

        $this->stdout('======Client: '.$client->name . PHP_EOL);
        foreach ($client->sites as $site) {
            $this->stdout('---SITE: '.$site->domain . PHP_EOL);
            \Yii::$app->settings->initSite($site_id);

            //очищаем индекс
            $this->stdout('Clearing' . PHP_EOL);
            $this->indexer->clear(SearchHelper::indexNameBackend());
            $this->indexer->clear(SearchHelper::indexNameFrontend());


            $query = Product::find()
                ->active()
                ->with(['category', 'categoryAssignments', 'tagAssignments', 'values','sites'])
                ->orderBy('id');


            $this->stdout('Indexing of products' . PHP_EOL);

            foreach ($query->each() as $product) {
                /** @var Product $product */
                $this->stdout('Product #' . $product->id . PHP_EOL);
                $this->indexer->index($product);
            }

            $this->stdout('Done!' . PHP_EOL);
        }

    }
    public function actionCreateIndex($client_id=null): void
    {
        if (empty($client_id)) {
            $clients=\rent\entities\Client\Client::find()->all();
            foreach ($clients as $client) {
                $this->actionCreateIndex($client->id);
            }
        }

        if ($client=\rent\entities\Client\Client::findOne($client_id)) {
            \Yii::$app->settings->initClient($client->id);
            $this->indexer->createIndex(SearchHelper::indexNameBackend());
            foreach ($client->sites as $site) {
                \Yii::$app->settings->initSite($site->id);
                $this->indexer->createIndex(SearchHelper::indexNameFrontend());
            }
        }
    }
    public function actionDeleteIndex($client_id=null): void
    {
        if (empty($client_id)) {
            $clients=\rent\entities\Client\Client::find()->all();
            foreach ($clients as $client) {
                $this->actionDeleteIndex($client->id);
            }
        }

        if ($client=\rent\entities\Client\Client::findOne($client_id)) {
            \Yii::$app->settings->initClient($client->id);
            $this->indexer->deleteIndex(SearchHelper::indexNameBackend());
            foreach ($client->sites as $site) {
                \Yii::$app->settings->initSite($site->id);
                $this->indexer->deleteIndex(SearchHelper::indexNameFrontend());
            }
        }
    }
}