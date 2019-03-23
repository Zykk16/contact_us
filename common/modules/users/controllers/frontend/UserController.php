<?php

namespace common\modules\users\controllers\frontend;

use common\modules\users\models as models;
use yii\web\Controller;
use yii\widgets\ActiveForm;
use yii\web\Response;
use Yii;

/**
 * Контроллер для управления пользователями
 * @package common\modules\users\controllers\frontend
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->module->viewPath = '@common/modules/users/views/frontend';
            return true;
        } else {
            return false;
        }
    }

    /**
     * Выход
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Профиль
     * @return array|string|Response
     */
    public function actionProfile()
    {
        $model = models\Profile::findByUserId(Yii::$app->user->id);
        $model->setScenario('signup');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCESS_UPDATE'));
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_UPDATE'));
                }
                return $this->refresh();
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('profile', [
            'model' => $model
        ]);
    }

    /**
     * Изменить пароль
     * @return array|string|Response
     */
    public function actionPassword()
    {
        $model = new models\frontend\PasswordForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->password()) {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCESS_PASSWORD_CHANGE'));
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_PASSWORD_CHANGE'));
                }
                return $this->refresh();
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('password', [
            'model' => $model
        ]);
    }

    /**
     * Изменить данные юридического лица
     * @return array|string|Response
     */
    public function actionLegalPerson()
    {
        if ($model = models\LegalPerson::findByUserId(Yii::$app->user->id)) {
            $model->scenario = 'user-create';
        } else {
            $model = new models\LegalPerson(['scenario' => 'user-update']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCESS_LEGAL_PERSON_UPDATE'));
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_LEGAL_PERSON_UPDATE'));
                }
                return $this->refresh();
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('legal-person', [
            'model' => $model
        ]);
    }
}
