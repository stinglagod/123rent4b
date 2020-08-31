<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\BadRequestHttpException;
use common\models\PasswordResetRequestForm;
use common\models\ResetPasswordForm;
//use common\models\SignupForm;
use rent\entities\User\User;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

     public function actionAccessDenied()
    {
//        $this->layout = 'main-login';
        return $this->render('access-denied');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($message=$model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'На электронный адрес выслано письмо для последующих инструкций');
                return $this->redirect(['index']);
//                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Извините, мы не можем сбросить пароль для указанного адреса электронной почты. Ошибка: '.$message);
            }
        }
        $this->layout = 'main-login';
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль сохранен. Вы можете авторизоваться с новым паролем');
            return $this->redirect(['index']);
//            return $this->goHome();
        }

        $this->layout = 'main-login';
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    public function actionRequestPasswordResetById($id)
    {
        $model = new PasswordResetRequestForm();
        $model->email=User::findIdentity($id)->email;
        if ($model->sendEmail()) {
            Yii::$app->session->setFlash('success', 'На электронный адрес ('.$model->email.') выслано письмо для последующих инструкций');
//            return $this->goHome();
        } else {
            Yii::$app->session->setFlash('error', 'Извините, мы не можем сбросить пароль для указанного адреса электронной почты.');
        }
        return $this->redirect(['user/update', 'id' => $id]);
    }
    public function actionPhpInfo()
    {
        return phpinfo();
    }
}
