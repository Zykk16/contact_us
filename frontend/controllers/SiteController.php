<?php

namespace frontend\controllers;

use backend\models\Slider;
use common\components\GoogleApiComponent;
use common\models\Settings;
use Google_Client;
use Google_Http_MediaFileUpload;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use kartik\mpdf\Pdf;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\BaseFileHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Slider();

        $setting = new Settings();
        $set = $setting->find()->all();

        $res = $model->find()->where(['flag' => '1'])->orderBy(['id' => SORT_DESC])->all();
        return $this->render('about', ['model' => $res, 'setting' => $set]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        $model->getDep4Cont();

        $content = $model->getContent4Google();
        $content = $this->renderPartial('_reportView', ['content' => $content]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_FILE,
            //Сделал отдельное название файла по дате и времени и сохраняю в отдельную папку для отправки в google
            'filename' => Yii::getAlias('@frontend') . "/web/pdf" . '/' . date("Ymd_His") . '.pdf',
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => 'Game Insight'],
            'methods' => [
                'SetHeader' => ['Game Insight'],
                'SetFooter' => ['{PAGENO}'],
            ],
        ]);


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->saveContact();
            $pdf->render();
            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionCallgdrive() {
        $client = new Google_Client();
        $client->setApplicationName("Test");
        $client->setAuthConfig(Yii::getAlias('@common') . '/credentials.json');

        $client->setRedirectUri('http://' . $_SERVER['SERVER_NAME'] . Url::to(['site/callgdrive']));
        $client->addScope(Google_Service_Drive::DRIVE);
        $client->setAccessType('offline');

        if (!isset($_GET['code'])) {
            $auth_url = $client->createAuthUrl();
            $this->redirect($auth_url);
        } else {
            $client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $client->getAccessToken();
            $this->redirect(['site/callgdrive']);
        }
    }

    public function actionGoogle() {
        $client = new Google_Client();
        $client->setApplicationName("test");
        $client->setAuthConfig(Yii::getAlias('@common') . '/credentials.json');
        $client->addScope(Google_Service_Drive::DRIVE);
        $client->setAccessType('offline');

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $client->setAccessToken($_SESSION['access_token']);
            if ($client->isAccessTokenExpired()) {
                $refreshToken = $this->getRefreshTokenFromDatabase();
                $client->refreshToken($refreshToken);
                $newAccessToken = $client->getAccessToken();
                $newAccessToken['refresh_token'] = $refreshToken;
                $this->setRefreshTokenIntoDatabase($newAccessToken);
                $_SESSION['access_token'] = $newAccessToken;
            }
            set_time_limit(0);
            $gdrive_service = new Google_Service_Drive($client);
            $file = new Google_Service_Drive_DriveFile();
            $folderId = 'dfghjtyguygyuuuuuuuuuuu';
            $file->setName($filename);
            $file->setParents([$folderId]);
            $chunkSizeBytes = 1 * 1024 * 1024;

            $client->setDefer(true);
            $request = $gdrive_service->files->create($file);

            $media = new Google_Http_MediaFileUpload(
                $client, $request, 'application/zip', null, true, $chunkSizeBytes
            );
            $media->setFileSize(filesize(Yii::getAlias('@webroot').'/content/' . $filename));

            $status = false;
            $handle = fopen(Yii::getAlias('@webroot').'/content/' . $filename, "rb");
            while (!$status && !feof($handle)) {
                $chunk = fread($handle, $chunkSizeBytes);
                $status = $media->nextChunk($chunk);
            }

            $result = false;
            if ($status != false) {
                $result = $status;
                $fileId = $result->getId();
            }
            fclose($handle);
            $client->setDefer(false);

        } else {
            $this->actionCallgdrive();
        }
    }


    /**
     * Displays about page.
     *
     * @return mixed
     */
//    public function actionAbout()
//    {
//        $model = new Slider();
//        $res = $model->find()->where(['flag' => '1'])->orderBy(['id' => SORT_DESC])->all();
//        return $this->render('about', ['model' => $res]);
//    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
//    public function actionSignup()
//    {
//        $model = new SignupForm();
//        if ($model->load(Yii::$app->request->post())) {
//            if ($user = $model->signup()) {
//                if (Yii::$app->getUser()->login($user)) {
//                    return $this->goHome();
//                }
//            }
//        }
//
//        return $this->render('signup', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
//    public function actionRequestPasswordReset()
//    {
//        $model = new PasswordResetRequestForm();
//        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            if ($model->sendEmail()) {
//                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
//
//                return $this->goHome();
//            } else {
//                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
//            }
//        }
//
//        return $this->render('requestPasswordResetToken', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
//    public function actionResetPassword($token)
//    {
//        try {
//            $model = new ResetPasswordForm($token);
//        } catch (InvalidArgumentException $e) {
//            throw new BadRequestHttpException($e->getMessage());
//        }
//
//        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
//            Yii::$app->session->setFlash('success', 'New password saved.');
//
//            return $this->goHome();
//        }
//
//        return $this->render('resetPassword', [
//            'model' => $model,
//        ]);
//    }

//    public function actionReport()
//    {
//        $model = new ContactForm();
//
//    }
}
