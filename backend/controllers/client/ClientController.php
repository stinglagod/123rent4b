<?php

namespace backend\controllers\client;

use rent\forms\auth\SignupForm;
use rent\forms\manage\Client\ClientCreateForm;
use rent\forms\manage\Client\ClientEditForm;
use rent\forms\manage\Client\InviteForm;
use rent\forms\manage\User\UserCreateForm;
use rent\services\manage\Client\ClientManageService;
use Yii;
use rent\entities\Client\Client;
use backend\forms\Client\ClientSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
{
    private $service;

    public function __construct($id, $module, ClientManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
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
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Client model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $invite=new UserCreateForm();
        if ($invite->load(Yii::$app->request->post()) && $invite->validate()) {
            $client = $this->findModel($id);
            try {
                $this->service->invite($client->id,$invite);
                $invite=new UserCreateForm();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'invite' =>$invite,
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new ClientCreateForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $brand = $this->service->create($form);
                return $this->redirect(['view', 'id' => $brand->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        $client = $this->findModel($id);

        $form = new ClientEditForm($client);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($client->id, $form);
                return $this->redirect(['view', 'id' => $client->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'client' => $client,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->service->remove($id);
        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @param $user_id
     * @return mixed
     */
    public function actionDeleteUser($id, $user_id)
    {
        try {
            $this->service->removeUser($id, $user_id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'id' => $id, '#' => 'users']);
    }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return \rent\entities\Client\Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Client::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
