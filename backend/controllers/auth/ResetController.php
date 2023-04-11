<?php
namespace backend\controllers\auth;

use rent\entities\Client\Site;
use rent\repositories\Client\SiteRepository;
use rent\useCases\auth\PasswordResetService;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use rent\forms\auth\PasswordResetRequestForm;
use rent\forms\auth\ResetPasswordForm;

class ResetController extends Controller
{
    private $service;
    private $sites;

    public function __construct($id,
                                $module,
                                PasswordResetService $service,
                                SiteRepository $sites,
                                $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->sites = $sites;
    }

    /**
     * @return mixed
     */
    public function actionRequest()
    {
        $form = new PasswordResetRequestForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            /** @var Site $site */
            if (!$site=$this->sites->findByDomain(Yii::$app->request->getHostName())) {
                throw new \DomainException('Не найден сайт: '.Yii::$app->request->getHostName());
            }
            Yii::$app->settings->initClient($site->client_id);

            try {
                $this->service->request($form);
                Yii::$app->session->setFlash('success', 'Проверьте вашу эл.почту для дальнейших инструкций.');
                return $this->goHome();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('request', [
            'model' => $form,
        ]);
    }

    /**
     * @param $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionConfirm($token)
    {
        try {
            $this->service->validateToken($token);
        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $form = new ResetPasswordForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->reset($token, $form);
                Yii::$app->session->setFlash('success', 'Новый пароль успешно сохранен');
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->goHome();
        }

        return $this->render('confirm', [
            'model' => $form,
        ]);
    }
}