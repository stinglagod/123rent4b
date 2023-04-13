<?php

namespace backend\controllers\support;

use backend\forms\support\CommentSearch;
use backend\forms\support\TaskSearch;
use rent\entities\Client\Client;
use rent\entities\Support\Task\Task;
use rent\forms\support\task\CommentForm;
use rent\forms\support\task\TaskForm;
use rent\repositories\support\TaskRepository;
use rent\useCases\support\SupportService;
use unit\forms\auth\AdminSignupFormTest;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TaskController extends Controller
{

    private SupportService $service;

    public function __construct($id, $module, SupportService $service, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
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
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(?int $client_id=null)
    {
        $form=new TaskForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $client=$client_id?$this->findClient($client_id):null;
            try {
                $task = $this->service->createTask($form,$client);
                Yii::$app->session->setFlash('success', 'Успешно создана заявка №'.$task->id);
                return $this->redirect(['view','id'=>$task->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }
    public function actionView(int $id)
    {
        $task=$this->findModel($id);
        $form=new TaskForm($task);
        $commentForm=new CommentForm();
        $post=Yii::$app->request->post();

        if($post) {
            //если получаем данные от editable
            if (isset($post['hasEditable'])) {
                //подразумевается, что за раз меняется только один атрибут
                $attributeName = array_key_first($post[$form->formName()]);
                $oldValue = $form->$attributeName;
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if ($form->load($post) && $form->validate()) {
                    try {
                        $this->service->editTask($task, $form);
                        return ['output' => $form->getValue($attributeName), 'message' => ''];
                    } catch (\DomainException $e) {
                        Yii::$app->errorHandler->logException($e);
                        return ['output' => $oldValue, 'message' => $e->getMessage()];
                    }
                }
                return ['output' => $oldValue, 'message' => $form->getFirstError($attributeName)];
            }
            //добавляем комментарий
            if ($commentForm->load($post) && $commentForm->validate()) {
                try {
                    $comment = $this->service->addComment($task, $commentForm);
                    Yii::$app->session->setFlash('success', 'Комментарий добавлен');
                    return $this->redirect(['view', 'id' => $task->id]);

                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        }
        $searchModelComment = new CommentSearch($task->id);
        $dataProviderComment= $searchModelComment->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'entity' => $form,
            'commentForm' => $commentForm,
            'dataProviderComment'=>$dataProviderComment

        ]);
    }

    ###
    protected function findModel($id): Task
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function findClient($clientId):Client
    {
        if (($model = Client::findOne($clientId)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}