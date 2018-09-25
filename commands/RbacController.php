<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\components\helpers\Console;
use app\components\Rbac;

/**
 * Class RbacController
 * @package app\commands
 */
class RbacController extends Controller
{
    public $color = true;

    /**
     * Generate roles and permissions
     *
     * @throws \yii\base\Exception
     */
    public function actionInit()
    {
        $auth = Yii::$app->getAuthManager();
        $auth->removeAll();

        // Просмотр таблицы
        $viewTable = $auth->createPermission(Rbac::PERMISSION_VIEW_TABLE);
        $viewTable->description = Rbac::PERMISSION_DESCRIPTION_VIEW_TABLE;
        $auth->add($viewTable);

        // Редактирование таблицы
        $editTable = $auth->createPermission(Rbac::PERMISSION_EDIT_TABLE);
        $editTable->description = Rbac::PERMISSION_DESCRIPTION_EDIT_TABLE;
        $auth->add($editTable);

        // Пользователь
        $user = $auth->createRole(Rbac::ROLE_USER);
        $user->description = Rbac::ROLE_DESCRIPTION_USER;
        $auth->add($user);

        // Админ
        $admin = $auth->createRole(Rbac::ROLE_ADMIN);
        $admin->description = Rbac::ROLE_DESCRIPTION_ADMIN;
        $auth->add($admin);

        // Разрешения для Пользователя
        $auth->addChild($user, $viewTable);

        // Разрешения для админа
        $auth->addChild($admin, $viewTable);
        $auth->addChild($admin, $editTable);

        $this->stdout(Console::convertEncoding(Yii::t('app', 'Done!')), Console::FG_GREEN, Console::BOLD);
    }
}
