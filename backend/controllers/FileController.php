<?php

namespace backend\controllers;

use Yii;
use common\models\File;
use backend\models\FileSearch;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\MultipleUploadForm;
use yii\web\UploadedFile;

/**
 * FileController implements the CRUD actions for File model.
 */
class FileController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index','view','gallery'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index','view','update','create','upload','gallery'],
                        'allow' => true,
                        'roles' => ['foreman'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['manager'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all File models.
     * @return mixed
     */
    public function actionIndex($hash=null)
    {
//        print_r(Yii::$app->request->queryParams);exit;
        $searchModel = new FileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isPjax) {
            $html = $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
            return $html;

        }elseif (Yii::$app->request->isAjax){
            $html = $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
            return Json::encode($html);
        } else {
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single File model.
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
     * Creates a new File model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new File();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing File model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing File model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $hash=$model->hash;
        $tmp=$model->delete();
        $tmp="<pre>".print_r($model)."</pre>";

        if (Yii::$app->request->isAjax) {
//            return $this->renderAjax(['index', 'hash' => $hash]);
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $tmp;
//            return [
//                'remove' => $tmp
//            ];
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the File model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return File the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = File::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionUpload($id=null)
    {
        if (empty($_FILES['files'])) {
            echo json_encode(['error'=>'Нет файлов для загрузки']);
            // or you can throw an exception
            return; // terminate
        }
        // get the files posted
        $files = $_FILES['files'];

        // get user id posted
        $contractid = empty($_POST['contract_id']) ? $id : $_POST['contract_id'];
//        $contractid = $id;

        $hash = empty($_POST['hash']) ? '' : $_POST['hash'];

        // a flag to see if everything is ok
        $success = null;

        // file paths to store
        $paths= [];

        // get file names
        $filenames = $files['name'];

        // loop and process files
        for($i=0; $i < count($filenames); $i++){
            $ext = explode('.', basename($filenames[$i]));
            $ext=array_pop($ext);

            $modelFile = new File();
            $modelFile->hash=$hash;
            $modelFile->ext=$ext;
            $modelFile->name=$filenames[$i];

            if ($modelFile->save()) {
                if(move_uploaded_file($files['tmp_name'][$i], $modelFile->getPath())) {
                    $success = true;
                } else {
                    $success = false;
                    $modelFile->delete();
                    break;
                }
            }


        }

        // check and process based on successful status
        if ($success === true) {
            $output = [];
        } elseif ($success === false) {
            $output = ['error'=>'Error while uploading images. Contact the system administrator'];
        } else {
            $output = ['error'=>'No files were processed.'];
        }
        // return a json encoded response for plugin to process successfully
        echo json_encode($output);
    }

    public function actionGallery($hash=null,$main=0)
    {
        $models=File::getFiles($hash);

        $arrtmp=array();
        foreach ($models as $model) {
            if ($model->format===File::VIDEO) {
                $arrtmp[]=[
                    'thumb'=> $model->thumb,
//                    'poster'=> 'https://sachinchoolur.github.io/lightGallery/static/img/thumb-v-y-1.jpg',
                    'html'=> '<video class="lg-video-object lg-html5" controls="controls" preload="none" autostart="false" autoplay=""><source src="'.$model->url.'" type="video/mp4">Your browser does not support HTML5 video</video>',
//                    'html'=> '<video class="lg-video-object lg-html5" controls preload="none"><source src="http://sachinchoolur.github.io/lightGallery/static/videos/video1.mp4" type="video/mp4">Your browser does not support HTML5 video</video>',
                ];
            } else {
                $arrtmp[]=[
                    'src'=> $model->url,
                    'thumb' => $model->thumb,
                ];
            }

        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $arrtmp;
    }
    public function actionOperation()
    {
//        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!empty($_POST['operation'])) {
            foreach ($_POST['selection'] as $id) {
                $model=$this->findModel($id);
                if ($_POST['operation']==File::DELETE) {
                    $model->delete();
                } elseif ($_POST['operation']==File::PUBLICATION) {
                    $model->private=0;
                    $model->save();
                } elseif ($_POST['operation']==File::NOTPUBLICATION) {
                    $model->private=1;
                    $model->save();
                } elseif ($_POST['operation']==File::MAIN) {
                    $model->main=1;
                    $model->save();
                } elseif ($_POST['operation']==File::NOTMAIN) {
                    $model->main=0;
                    $model->save();
                }

            }

            return $this->goBack();
//            удалить

        }
    }
}
