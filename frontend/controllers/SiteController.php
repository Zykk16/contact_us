<?php

namespace frontend\controllers;

use backend\models\Slider;
use common\components\GoogleApiComponent;
use common\models\Settings;
use kartik\mpdf\Pdf;
use Yii;
use yii\db\AfterSaveEvent;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
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
     * @throws \yii\base\InvalidConfigException
     */
    public function actionContact()
    {
        $model = new ContactForm();
        $model->getDep4Cont();



        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //Сохраняю заявку
            $model->saveContact();
            $content = $model->getContent4Google();

            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_FILE,
                //Сделал отдельное название файла по дате и времени и сохраняю в отдельную папку для отправки в google
                'filename' => Yii::getAlias('@frontend') . "/web/pdf/" . date("Ymd_His") . '.pdf',
                'content' => $this->renderPartial('_reportView', ['content' => $content]),
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}',
                'options' => ['title' => 'Game Insight'],
                'methods' => [
                    'SetHeader' => ['Game Insight'],
                    'SetFooter' => ['{PAGENO}'],
                ],
            ]);
            //рендерю pdf
            $pdf->render();
            //редирект в гугл
            return $this->redirect(['site/calldrive']);
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionCalldrive()
    {
        $api = new GoogleApiComponent();
        $api->createDocumentGoogleApi();
        //и редирект обратно
        return $this->redirect(['site/contact']);
    }
}
