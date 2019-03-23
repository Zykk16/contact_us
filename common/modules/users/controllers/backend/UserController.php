<?php

namespace common\modules\users\controllers\backend;

use common\modules\users\models as models;
use nepster\users\models\Profile;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use Yii;

/**
 * Контроллер для управления пользователями
 * @package common\modules\users\controllers\backend
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
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => $this->module->accessGroupsToControlpanel,
                    ],
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
            $this->module->viewPath = '@common/modules/users/views/backend';
            return true;
        }
        return false;
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
     * Все пользователи
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('user-view')) {
            throw new ForbiddenHttpException(Yii::t('users.rbac', 'ACCESS_DENIED'));
        }

        $searchModel = new models\backend\search\UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Создать пользователя
     * Если пользователь будет создан, то сработает редирект на index
     * @return array|string|Response
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('user-create')) {
            throw new ForbiddenHttpException(Yii::t('users.rbac', 'ACCESS_DENIED'));
        }

        $user = new models\backend\User(['scenario' => 'create']);
        $profile = new models\backend\Profile(['scenario' => 'create']);
        $person = new models\backend\LegalPerson(['scenario' => 'create']);

        if (Yii::$app->request->isPost) {
            $user->load(Yii::$app->request->post());
            $profile->load(Yii::$app->request->post());
            $person->load(Yii::$app->request->post());
            if (!ActiveForm::validateMultiple([$user, $profile, $person])) {
                $user->populateRelation('profile', $profile);
                $user->populateRelation('person', $person);
                if ($user->save(false)) {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCESS_UPDATE'));
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_UPDATE'));
                }
                return $this->refresh();
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validateMultiple([$user, $profile, $person]);
            }
        }

        return $this->render('create', [
            'user' => $user,
            'profile' => $profile,
            'person' => $person,
        ]);
    }

    /**
     * Редактировать пользователя
     * @param $id
     * @return array|string|Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('user-view')) {
            throw new ForbiddenHttpException(Yii::t('users.rbac', 'ACCESS_DENIED'));
        }

        $user = $this->findModel($id);
        $user->scenario = 'update';
        $profile = new models\backend\Profile(['scenario' => 'create']);
        $person = new models\backend\LegalPerson(['scenario' => 'create']);
        $user->password = ''; // Сброс пароля

        if ($user->profile) {
            $profile = $user->profile;
            $profile->scenario = 'update';
        }

        if ($user->person) {
            $person = $user->person;
            $person->scenario = 'update';
        }

        // Разблокировать пользователя
        if (isset(Yii::$app->request->get()['rebanned'])) {
            if (Yii::$app->user->reBannedByUser($user->id)) {
                Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCESS_UPDATE'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_UPDATE'));
            }
            return $this->redirect(['update', 'id' => $user->id]);
        }

        if (Yii::$app->request->isPost) {

            if (!Yii::$app->user->can('user-update')) {
                throw new ForbiddenHttpException(Yii::t('users.rbac', 'ACCESS_DENIED'));
            }

            $user->load(Yii::$app->request->post());
            $profile->load(Yii::$app->request->post());
            $person->load(Yii::$app->request->post());
            if (!ActiveForm::validateMultiple([$user, $profile, $person])) {
                $user->populateRelation('profile', $profile);
                $user->populateRelation('person', $person);
                if ($user->save(false)) {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCESS_UPDATE'));
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_UPDATE'));
                }
                return $this->refresh();
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validateMultiple([$user, $profile, $person]);
            }
        }

        return $this->render('update', [
            'user' => $user,
            'profile' => $profile,
            'person' => $person,
        ]);
    }

    /**
     * Удалить пользователя
     * Если пользователь будет удален, то сработает редирект на index
     * @param $id
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('user-delete')) {
            throw new ForbiddenHttpException(Yii::t('users.rbac', 'ACCESS_DENIED'));
        }

        $user = $this->findModel($id);
        $user->status = $user::STATUS_DELETED;
        if ($user->save(false)) {
            Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCESS_UPDATE'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_UPDATE'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Массовое управление пользователями
     * @return Response
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionMultiControl()
    {
        if (!Yii::$app->user->can('user-multi-control')) {
            throw new ForbiddenHttpException(Yii::t('users.rbac', 'ACCESS_DENIED'));
        }

        $params = Yii::$app->request->post('selection');
        $params = is_array($params) ? $params : [];
        $users = models\User::findIdentities($params);

        if ($users) {

            switch (Yii::$app->request->post('action')) {

                case 'sendemail': // Отправить письмо
                    $ids = [];
                    foreach ($users as $user) {
                        $ids[] = $user->id;
                    }
                    return $this->redirect(['send-email', 'ids' => $ids]);
                    break;

                case 'rebanned': // Разблокировать
                    foreach ($users as $user) {
                        Yii::$app->user->reBannedByUser($user->id);
                    }
                    break;

                case 'banned': // Заблокировать
                    foreach ($users as $user) {
                        if ($user->id != Yii::$app->user->id) {
                            Yii::$app->user->bannedByUser($user->id);
                        }
                    }
                    break;

                case 'deleted': // Удалить
                    foreach ($users as $user) {
                        if ($user->id != Yii::$app->user->id) {
                            $user->status = models\User::STATUS_DELETED;
                            $user->save(false);
                        }
                    }
                    break;

                case 'recover': // Восстановить
                    foreach ($users as $user) {
                        $user->status = models\User::STATUS_ACTIVE;
                        $user->save(false);
                    }
                    break;

                default:
                    Yii::$app->session->setFlash('success', Yii::t('users', 'ACTION_INCORRECT'));
                    return $this->redirect(['index']);
            }

            Yii::$app->user->action(Yii::$app->user->id, $this->module->id, 'multi-control', [Yii::$app->request->post('action') => $params]);

            Yii::$app->session->setFlash('success', Yii::t('users', 'ACTIONS_MADE'));

        } else {

            Yii::$app->session->setFlash('danger', Yii::t('users', 'NO_SET_USERS'));

        }

        return $this->redirect(['index']);
    }

    /**
     * Отправить сообщение
     * @return array|string|Response
     * @throws ForbiddenHttpException
     */
    public function actionSendEmail()
    {
        if (!Yii::$app->user->can('user-send-email')) {
            throw new ForbiddenHttpException(Yii::t('users.rbac', 'ACCESS_DENIED'));
        }

        $users = models\User::findIdentities(Yii::$app->request->get('ids'));
        $model = new \nepster\users\models\SendEmail();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                $model->setUsers($users);
                if ($model->send(true)) {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'Задание поставлено в очередь'));
                }
                return $this->refresh();
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('send-email', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    /**
     * Заблокировать пользователей
     * @return array|string|Response
     * @throws ForbiddenHttpException
     */
    public function actionBanned()
    {
        if (!Yii::$app->user->can('user-banned')) {
            throw new ForbiddenHttpException(Yii::t('users.rbac', 'ACCESS_DENIED'));
        }

        $users = models\User::findIdentities(Yii::$app->request->get('ids'));
        $model = new \nepster\users\models\Banned();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                if ($model->bannedUsers($users)) {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCESS_UPDATE'));
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_UPDATE'));
                }
                return $this->refresh();
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('banned', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    /**
     * Находит модель пользователя на основе значения первичного ключа.
     * Если модель не найдена, будет сгенерировано исключение HTTP 404.
     * @param integer $id
     * @return models\User
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = models\backend\User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('users', 'USER_NOT_FOUND'));
    }
}
