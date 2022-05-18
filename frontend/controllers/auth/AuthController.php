<?php
namespace frontend\controllers\auth;

use rent\forms\auth\SignupForm;
use rent\useCases\auth\AuthService;
use Yii;
use yii\web\Controller;
use rent\forms\auth\LoginForm;

class AuthController extends Controller
{
    private $service;

    public function __construct($id, $module, AuthService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new LoginForm();
        $signupForm= new SignupForm();

//        dump($form->load(Yii::$app->request->post()));
//        dump( $form->validate());

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->service->auth($form);
                Yii::$app->user->login($user, $form->rememberMe ? 3600 * 24 * 30 : 0);
                Yii::$app->session->setFlash('success', 'Вы успешно вошли');
//                dump($form->load(Yii::$app->request->post()));
//                dump( $form->validate());
//                exit;

                return $this->goHome();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('login', [
            'model' => $form,
            'signup' => $signupForm,
            'focus' => 'login'
        ]);
    }

    /**
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}