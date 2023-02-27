<?php

namespace backend\controllers\crm;

use backend\forms\CRM\ContactSearch;
use rent\entities\CRM\Contact;
use rent\forms\manage\CRM\ContactForm;
use rent\forms\manage\Shop\TagForm;
use rent\useCases\manage\CRM\ContactManageService;
use rent\useCases\manage\Shop\TagManageService;
use Yii;
use rent\entities\Shop\Tag;
use backend\forms\Shop\TagSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ContactController extends Controller
{
    private ContactManageService $service;

    public function __construct($id, $module, ContactManageService $service, $config = [])
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
        $searchModel = new ContactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $modalCreateForm= $this->renderPartial('_modalCreate',[
            'model' => new ContactForm(),
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modalCreateForm'=>$modalCreateForm
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new ContactForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $contact = $this->service->create($form);
                return $this->redirect(['view', 'id' => $contact->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionCreateAjax()
    {
        $form = new ContactForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $contact = $this->service->create($form);
                return $this->asJson([
                    'success' => true,
                    'data' => [
                        'contactId'=>$contact->id,
                        'contactName'=>$contact->name,
                    ]
                ]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
                return ['status' => 'success', 'data' => ''];
                $result = [];
                // The code below comes from ActiveForm::validate(). We do not need to validate the model
                // again, as it was already validated by save(). Just collect the messages.
                foreach ($form->getErrors() as $attribute => $errors) {
                    $result[yii\helpers\Html::getInputId($form, $attribute)] = $errors;
                }
                return $this->asJson(['validation' => $result]);
            }
        }
    }
    /**
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $contact = $this->findModel($id);

        $form = new ContactForm($contact);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($contact->id, $form);
                return $this->redirect(['view', 'id' => $contact->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'contact' => $contact,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
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
     * @return Contact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Contact
    {
        if (($model = Contact::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
