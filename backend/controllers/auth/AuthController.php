<?php
namespace backend\controllers\auth;

use common\auth\Identity;
use rent\forms\auth\AdminSignupForm;
use rent\forms\auth\LoginForm;
use rent\useCases\auth\AuthService;
use rent\useCases\auth\SignupService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

class AuthController extends Controller
{
    private AuthService $authService;
    private SignupService $signupService;

    public function __construct($id, $module, AuthService $service, SignupService $signupService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->authService = $service;
        $this->signupService = $signupService;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'main-login';

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->authService->auth($form);
                Yii::$app->params['cookieDomain']='.deco-rent.test';
                if (Yii::$app->user->login(new Identity($user), $form->rememberMe ? 3600 * 24 * 30 : 0)) {
                }else {
                    Yii::$app->errorHandler->logException('При авторизации произошла ошибка. Свяжитесь с администратором');
                    Yii::$app->session->setFlash('error', 'При авторизации произошла ошибка. Свяжитесь с администратором');
                }
                return $this->goBack();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('login', [
            'model' => $form,
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
