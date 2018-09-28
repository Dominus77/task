<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\ArrayHelper;
use app\components\helpers\Console;
use app\models\User;

/**
 * Class RoleController
 * @package app\commands
 */
class RoleController extends Controller
{
    public $color = true;

    /**
     * Assign a role to the user
     *
     * @throws Exception
     */
    public function actionAssign()
    {
        $username = $this->prompt(Console::convertEncoding(Yii::t('app', 'Username')) . ':', ['required' => true]);
        $user = $this->findModel($username);
        $roleName = $this->select(Console::convertEncoding(Yii::t('app', 'Role')) . ':', ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'));
        $authManager = Yii::$app->getAuthManager();
        $role = $authManager->getRole($roleName);
        // Проверяем есть ли уже такая роль у пользователя
        $userRoles = $this->getUserRoleValue($user->id);
        if ($userRoles === null) {
            $authManager->assign($role, $user->id);
            $this->log(true);
        } else {
            $this->stdout(Console::convertEncoding(Yii::t('app', 'The user already has a role.')) . PHP_EOL, Console::FG_RED, Console::BOLD);
        }
    }

    /**
     * Revoke the role from the user
     *
     * @throws Exception
     */
    public function actionRevoke()
    {
        $username = $this->prompt(Console::convertEncoding(Yii::t('app', 'Username')) . ':', ['required' => true]);
        $user = $this->findModel($username);
        $roleName = $this->select(Console::convertEncoding(Yii::t('app', 'Role')) . ':', ArrayHelper::merge(
            ['all' => Console::convertEncoding(Yii::t('app', 'All Roles'))],
            ArrayHelper::map(Yii::$app->authManager->getRolesByUser($user->id), 'name', 'description'))
        );
        $authManager = Yii::$app->getAuthManager();
        if ($roleName == 'all') {
            $authManager->revokeAll($user->id);
        } else {
            $role = $authManager->getRole($roleName);
            $authManager->revoke($role, $user->id);
        }
        $this->log(true);
    }

    /**
     * @param string|int $user_id
     * @return mixed|null
     */
    public function getUserRoleValue($user_id)
    {
        $authManager = Yii::$app->authManager;
        if ($role = $authManager->getRolesByUser($user_id)) {
            return ArrayHelper::getValue($role, function ($role) {
                foreach ($role as $key => $value) {
                    return $value->name;
                }
                return null;
            });
        }
        return null;
    }

    /**
     * Return model User
     *
     * @param string $username
     * @return User|null
     * @throws Exception
     */
    private function findModel($username)
    {
        if (!$model = User::findOne(['username' => $username])) {
            throw new Exception(Yii::t('app', 'User "{:Username}" not found', [':Username' => $username]));
        }
        return $model;
    }

    /**
     * @param bool|int $success
     */
    private function log($success = false)
    {
        if ($success === true || $success !== 0) {
            $this->stdout(Console::convertEncoding(Yii::t('app', 'Done!')), Console::FG_GREEN, Console::BOLD);
        } else {
            $this->stderr(Console::convertEncoding(Yii::t('app', 'Fail!')), Console::FG_RED, Console::BOLD);
        }
        echo PHP_EOL;
    }
}
