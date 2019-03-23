<?php

namespace nepster\users\commands;

use nepster\users\models\User;
use yii\helpers\Console;
use yii\log\Logger;
use Yii;

/**
 * Консольные команды
 * @package nepster\users\commands
 */
class ControlController extends \yii\console\Controller
{
    /**
     * @var string
     */
    public $mailViewPath = '@common/modules/users/mails/';

    /**
     * @var \common\modules\users\models\User
     */
    private $_user;

    /**
     * Send Email
     *
     * @param $email
     * @param $view
     * @param $subject
     * @param $content
     *
     * Command: php yii users/control/send-email [email] [view] [subject] [content]
     */
    public function actionSendEmail($email, $view, $subject, $content = null)
    {
        $this->_user = User::find()
            ->where('email = :email', [':email' => $email])
            ->one();

        if ($this->_user) {

            $mail = Yii::$app->getMailer();
            $mail->viewPath = $this->mailViewPath;
            $send = $mail->compose($view, [
                    'user' => $this->_user,
                    'content' => $content
                ])
                ->setFrom(Yii::$app->getMailer()->messageConfig['from'])
                ->setTo($this->_user['email'])
                ->setSubject($subject)
                ->send();

            if ($send) {
                $this->stdout("SUCESS" . PHP_EOL, Console::FG_GREEN);
                Yii::getLogger()->log('SUCCESS: E-MAIL: ' . $this->_user['email'] . ', view: ' . $view, Logger::LEVEL_INFO, 'users.send');
            } else {
                $this->stdout("ERROR" . PHP_EOL, Console::FG_RED);
                Yii::getLogger()->log('ERROR: E-MAIL: ' . $this->_user['email'] . ', view: ' . $view, Logger::LEVEL_ERROR, 'users.send');
            }

        } else {
            Yii::getLogger()->log('ERROR: send fail. DATA: ' . $email . ', ' . $view . ', ' . $subject, Logger::LEVEL_ERROR, 'users.send');
        }
    }

    /**
     * Multi Send Email
     *
     * @param $view
     * @param $subject
     * @param $content
     * @param $emails
     *
     * Command: php yii users/control/multi-send-email [view] [subject] [content] [emails]
     */
    public function actionMultiSendEmail($view, $subject, $content, $emails)
    {
        $emails = func_get_args();
        unset($emails[0]);
        unset($emails[1]);
        unset($emails[2]);

        foreach ($emails as &$email) {
            $this->runAction('send-email', [$email, $view, $subject, $content]);
            sleep(0.5);
        }
    }

    /**
     * Import access rules
     *
     * Command: php yii users/control/rbac
     */
    public function actionRbac()
    {

        if ($this->confirm('Import access rules ?', true)) {

            $transaction = Yii::$app->db->beginTransaction();

            try {
                $auth = Yii::$app->authManager;

                // ГРУППЫ
                # ---------------------------------------------------
                // Группа "admin"
                $admin = $auth->createRole('admin');
                $admin->description = 'ADMINISTRATOR';
                $auth->add($admin);

                // Группа "user"
                $user = $auth->createRole('user');
                $user->description = 'USER';
                $auth->add($user);


                // РАЗРЕШЕНИЯ
                # ---------------------------------------------------
                // Создание пользователей
                $userCreate = $auth->createPermission('user-create');
                $userCreate->description = 'PERMISSION_USER_CREATE';
                $auth->add($userCreate);

                // Редактирование пользователей
                $userUpdate = $auth->createPermission('user-update');
                $userUpdate->description = 'PERMISSION_USER_UPDATE';
                $auth->add($userUpdate);

                // Просмотр пользователей
                $userView = $auth->createPermission('user-view');
                $userView->description = 'PERMISSION_USER_VIEW';
                $auth->add($userView);

                // Удаление пользователей
                $userDelete = $auth->createPermission('user-delete');
                $userDelete->description = 'PERMISSION_USER_DELETE';
                $auth->add($userDelete);

                // Блокировка пользователей
                $userBanned = $auth->createPermission('user-banned');
                $userBanned->description = 'PERMISSION_USER_BANNED';
                $auth->add($userBanned);

                // Массовое управление пользователями
                $userMultiControl = $auth->createPermission('user-multi-control');
                $userMultiControl->description = 'PERMISSION_USER_MULTI_CONTROL';
                $auth->add($userMultiControl);

                // Массовое управление пользователями
                $userSendEmail = $auth->createPermission('user-send-email');
                $userSendEmail->description = 'PERMISSION_USER_SEND_EMAIL';
                $auth->add($userSendEmail);

                // Просмотр действий пользователей
                $userActionsView = $auth->createPermission('user-actions-view');
                $userActionsView->description = 'PERMISSION_USER_ACTIONS_VIEW';
                $auth->add($userActionsView);

                // Управление правами доступа
                $userAccessRulesControl = $auth->createPermission('user-access-rules-control');
                $userAccessRulesControl->description = 'PERMISSION_USER_ACCESS_RULES_CONTROL';
                $auth->add($userAccessRulesControl);


                // ПРАВА ДОСТУПА
                # ---------------------------------------------------
                // Администратор
                $auth->addChild($admin, $userCreate);
                $auth->addChild($admin, $userUpdate);
                $auth->addChild($admin, $userView);
                $auth->addChild($admin, $userDelete);
                $auth->addChild($admin, $userBanned);
                $auth->addChild($admin, $userMultiControl);
                $auth->addChild($admin, $userSendEmail);
                $auth->addChild($admin, $userActionsView);
                $auth->addChild($admin, $userAccessRulesControl);


                // НАЗНАЧЕНИЕ ГРУПП
                # ---------------------------------------------------
                $auth->assign($admin, 1);


                $transaction->commit();
                $this->stdout('Import access rules success' . PHP_EOL, Console::FG_GREEN, Console::UNDERLINE);

            } catch (\Exception $e) {

                $transaction->rollBack();
                $this->stdout('Import access rules error' . PHP_EOL, Console::FG_RED, Console::UNDERLINE);

            }
        }
    }
}