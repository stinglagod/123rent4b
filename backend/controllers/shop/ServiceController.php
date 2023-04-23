<?php

namespace backend\controllers\shop;

use backend\forms\Shop\ServiceSearch;
use rent\entities\Shop\Service;
use rent\forms\manage\Shop\BrandForm;
use rent\forms\manage\Shop\ServiceForm;
use rent\useCases\manage\Shop\BrandManageService;
use rent\useCases\manage\Shop\ServiceManageService;
use Yii;
use rent\entities\Shop\Brand;
use backend\forms\Shop\BrandSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ServiceController extends Controller
{
    private ServiceManageService $service;

    public function __construct($id, $module, ServiceManageService $service, $config = [])
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
            'access' => [
                'class' => AccessControl::class,
                'only' => ['delete-force'],
                'rules' => [
                    [
                        'actions' => ['delete-force'],
                        'allow' => true,
                        'roles' => ['super_admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'service' => $this->findModel($id),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new ServiceForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $entity = $this->service->create($form);
                return $this->redirect(['view', 'id' => $entity->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }
    /**
     * @return mixed
     */
    public function actionCreateDefault()
    {
        try {
            $this->service->createDefault();
            return $this->redirect(['index']);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['index']);
        }
    }
    /**
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $service = $this->findModel($id);

        $form = new ServiceForm($service);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($service->id, $form);
//                return $this->redirect(['view', 'id' => $service->id]);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'service' => $service,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->service->onDelete($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }
    /**
     * Реальное жесткое удаление услуги. Только для администратора
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteForce($id)
    {
        try {
            $this->service->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return Service the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Service
    {
        if (($model = Service::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
