<?php

namespace backend\controllers\client;

use rent\entities\Client\Site;
use rent\forms\auth\SignupForm;
use rent\forms\manage\Client\ClientChangeForm;
use rent\forms\manage\Client\ClientCreateForm;
use rent\forms\manage\Client\ClientEditForm;
use rent\forms\manage\Client\InviteForm;
use rent\forms\manage\Client\Site\SiteChangeForm;
use rent\forms\manage\Client\Site\SiteForm;
use rent\forms\manage\User\UserCreateForm;
use rent\useCases\manage\Client\ClientManageService;
use Yii;
use rent\entities\Client\Client;
use backend\forms\Client\ClientSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

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
                    'delete-user' => ['POST'],
//                    'change-site' => ['POST'],
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
        $client = $this->findModel($id);

        $this->service->changeActiveClient($client->id);

        $invite=new UserCreateForm();
        $sitesProvider = new ActiveDataProvider([
            'query' => $client->getSites()->orderBy('name'),
            'key' => function (Site $site) use ($client) {
                return [
                    'client_id' => $client->id,
                    'id' => $site->id,
                ];
            },
            'pagination' => false,
        ]);
        if ($invite->load(Yii::$app->request->post()) && $invite->validate()) {

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
            'sitesProvider'=>$sitesProvider
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
                $client = $this->service->create($form);
                $this->service->changeActiveClient($client->id);
                return $this->redirect(['view', 'id' => $client->id]);
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
                $this->service->changeActiveClient($client->id);
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

    public function actionMakeOwnerUser($id, $user_id)
    {
        try {
            $this->service->makeOwnerUser($id, $user_id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'id' => $id, '#' => 'users']);
    }

    public function actionChangeClient()
    {
        $form = new ClientChangeForm();
        $status='success';
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->changeActiveClient($form->client_id);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
                $status='error';
            }
        }
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['data'=>'', 'status'=>$status];
        } else {
            return $this->goBack();
        }

    }

    public function actionChangeSite()
    {
        $form=new SiteChangeForm();
        $status='success';
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->changeActiveSite($form->site_id);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
                $status='error';
            }
        }
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['data'=>'', 'status'=>$status];
        } else {
            return $this->goBack();
        }
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

//    private function changeClient($id,$site_id=0)
//    {
//
//        Yii::$app->session->set('client_id',$id);
//        Yii::$app->session->set('site_id',$site_id);
//        Yii::$app->params['clientId']=$id;
//        Yii::$app->params['siteId']=$site_id;
//        Yii::$app->view->params['clientChangForm'] = new ClientChangeForm(
//            Yii::$app->params['clientId'],
//            Yii::$app->params['siteId']
//        );
//    }
}
