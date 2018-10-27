<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use backend\models\UserSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\PasswordResetRequestForm;


/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::class,
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//            'access' => [
//                'class' => AccessControl::class,
//                'only' => ['index', 'create', 'update', 'delete'],
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'actions' => ['index','create','create','update','delete'],
//                        'roles' => ['manager'],
//                    ],
//                    [
//                        'actions' => ['update'],
//                        'allow' => true,
//                        'roles' => ['user'],
//                    ],
//                ],
//            ],
//        ];
//    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //обновляем роли для пользователя
            if ($_POST['User']['role']) {
                $this->setRole($model->id,$_POST['User']['role']);
            } else {
                $this->setRole($model->id,['user']);
            }

//          Сбрасываем пароль
            $modelResetRequest = new PasswordResetRequestForm();
            $modelResetRequest->email=$model->email;
            if ($modelResetRequest->sendEmail()) {
                Yii::$app->session->setFlash('success', 'На электронный адрес ('.$modelResetRequest->email.') выслано письмо для последующих инструкций');
            } else {
                Yii::$app->session->setFlash('error', 'Извините, мы не можем сбросить пароль для указанного адреса электронной почты.');
            }
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!(\Yii::$app->user->can('manager')) && !(\Yii::$app->user->id==$id)) {
            return false;
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //обновляем роли для пользователя
            if (\Yii::$app->user->can('manager')) {
                $this->setRole($model->id,$_POST['User']['role']);
                return $this->redirect(['index']);
            } else {
                return $this->goHome();
            }

//            return $this->redirect(['view', 'id' => $model->id]);

        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
//        Очищаем права
        $authManager = \Yii::$app->get('authManager');
        $authManager->revokeAll($id);

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /**
     * Устанавливаем роль для пользователя
     * @param $id
     * @param $roles
     * @throws NotFoundHttpException
     */
    protected function setRole($id, $roles)
    {
        if(!empty( $roles ))
        {
            /** @var \yii\rbac\DbManager $authManager */
            $authManager = \Yii::$app->get('authManager');
            $authManager->revokeAll($id);

            foreach ($roles as $item)
            {
                $r = $authManager->createRole($item);
                $authManager->assign($r,$id);
            }
        }
        else
        {
            throw new NotFoundHttpException('Bad Request.');
        }

    }

    public function actionProfile($id)
    {
        $model=$this->findModel($id);
        $html = $this->renderPartial('profile', [
            'model' => $model,
        ]);
        return Json::encode($html);

    }
}
