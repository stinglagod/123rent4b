<?php

namespace backend\controllers\client;

use rent\entities\Client\Site;
use rent\forms\manage\Client\SiteForm;
use rent\forms\manage\Shop\Product\ModificationForm;
use rent\services\manage\Client\ClientManageService;
use rent\services\manage\Shop\ProductManageService;
use Yii;
use rent\entities\Shop\Product\Product;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use rent\entities\Client\Client;

class SiteController extends Controller
{
    private $service;

    public function __construct($id, $module, ClientManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect('client/client');
    }

    /**
     * @param $client_id
     * @return mixed
     */
    public function actionCreate($client_id)
    {
        $client = Client::findOne($client_id);

        if (count($client->sites) >= Yii::$app->params['numbSitesOfClient']) {
            Yii::$app->session->setFlash('error', 'Достигнут лимит по количеству сайтов');
            return $this->redirect(['client/client/view', 'id' => $client->id, '#' => 'sites']);
        }

        $form = new SiteForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->addSite($client->id, $form);
                return $this->redirect(['client/client/view', 'id' => $client->id, '#' => 'sites']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'client' => $client,
            'model' => $form,
        ]);
    }

    /**
     * @param integer $client_id
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($client_id, $id)
    {
        $client = Client::findOne($client_id);
        $site = $client->getSite($id);

        $form = new SiteForm($site);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->editSite($client->id, $site->id, $form);
                return $this->redirect(['client/client/view', 'id' => $client->id, '#' => 'sites']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'client' => $client,
            'model' => $form,
            'site' => $site,
        ]);
    }

    /**
     * @param $client_id
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($client_id, $id)
    {
        $client = Client::findOne($client_id);
        try {
            $this->service->removeSite($client->id, $id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['client/client/view', 'id' => $client->id, '#' => 'sites']);
    }

    /**
     * @param integer $id
     * @return Site the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Site
    {
        if (($model = Site::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
