<?php

namespace backend\controllers;

use rent\entities\Client\Client;
use common\models\File;
use rent\forms\manage\User\UserEditForm;
use rent\services\manage\UserManageService;
use Yii;
use rent\entities\User\User;
use backend\forms\UserSearch;
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
    private $service;

    public function __construct($id, $module, UserManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

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
//    public function actionCreate()
//    {
//        $model = new User();
//        $clients = Client::find()->all();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            //обновляем роли для пользователя
//            if ($_POST['User']['role']) {
//                $this->setRole($model->id,$_POST['User']['role']);
//            } else {
//                $this->setRole($model->id,['user']);
//            }
//
////          Сбрасываем пароль
//            $modelResetRequest = new PasswordResetRequestForm();
//            $modelResetRequest->email=$model->email;
//            if ($modelResetRequest->sendEmail()) {
//                Yii::$app->session->setFlash('success', 'На электронный адрес ('.$modelResetRequest->email.') выслано письмо для последующих инструкций');
//            } else {
//                Yii::$app->session->setFlash('error', 'Извините, мы не можем сбросить пароль для указанного адреса электронной почты.');
//            }
//            return $this->redirect(['update', 'id' => $model->id]);
//        }
//
//        return $this->render('create', [
//            'model' => $model,
//            'clients' => $clients
//        ]);
//    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);

        $form = new UserEditForm($user);
//        var_dump(Yii::$app->request->post());
//        var_dump(User::getAllRoles());exit;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($user->id, $form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'user' => $user,
        ]);
    }
    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
//    public function actionUpdate($id)
//    {
//        if (!(\Yii::$app->user->can('manager')) && !(\Yii::$app->user->id==$id)) {
//            return false;
//        }
//        $model = $this->findModel($id);
//        $clients = Client::find()->all();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            var_dump(Yii::$app->request->post());
//            var_dump($model->load(Yii::$app->request->post()));
//            var_dump($model);
//            var_dump('tut');exit;
//            //обновляем роли для пользователя
//            if (\Yii::$app->user->can('manager')) {
//                $this->setRole($model->id,$_POST['User']['role']);
//                return $this->redirect(['index']);
//            } else {
//                return $this->goHome();
//            }
//
////            return $this->redirect(['view', 'id' => $model->id]);
//
//        }
//
//        return $this->render('update', [
//            'model' => $model,
//            'clients' => $clients
//        ]);
//    }

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
     * @return \rent\entities\User\User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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
