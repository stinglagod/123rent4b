<?php

namespace backend\controllers\client;

use rent\entities\Client\Site;
use rent\forms\manage\Client\Site\SiteForm;
use rent\forms\manage\Shop\Product\ModificationForm;
use rent\services\manage\Client\ClientManageService;
use rent\services\manage\Client\SiteManageService;
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
    private $siteManageService;

    public function __construct($id, $module, ClientManageService $service, SiteManageService $siteManageService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->siteManageService = $siteManageService;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'list-dep-drop' => ['POST'],
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

        $form = new \rent\forms\manage\Client\Site\SiteForm();
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

        $form = new \rent\forms\manage\Client\Site\SiteForm($site);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->editSite($client->id, $site->id, $form);
                $this->service->changeActiveSite($client->id,$site->id);

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

    public function actionListDepDrop()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $client_id = $parents[0];
                $out = $this->service->getSitesArray($client_id);
                return ['output'=>$out, 'selected'=>''];
            }
        }
        return ['output'=>'', 'selected'=>''];
    }
### MainSlider
    public function actionMoveMain_sliderUp($id,$key)
    {
        $site=$this->findModel($id);
        $this->siteManageService->moveMainSliderUp($site, $key);
        return $this->redirect(['update', 'client_id' => $site->client_id, 'id'=>$site->id, '#' => 'site-tab1']);
    }
    public function actionMoveMain_sliderDown($id,$key)
    {
        $site=$this->findModel($id);
        $this->siteManageService->moveMainSliderDown($site, $key);
        return $this->redirect(['update', 'client_id' => $site->client_id, 'id'=>$site->id, '#' => 'site-tab1']);
    }
    public function actionDeleteMain_slider($id,$key)
    {
        $site=$this->findModel($id);
        $this->siteManageService->removeMainSlider($site, $key);
        return $this->redirect(['update', 'client_id' => $site->client_id, 'id'=>$site->id, '#' => 'site-tab1']);
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
