<?php

namespace app\commands;

use Yii;
use app\models\User;
use yii\console\Controller;
use yii\console\Exception;
use app\components\helpers\Console;

/**
 * Class UserController
 * @package app\commands
 */
class UserController extends Controller
{
    public $color = true;

    /**
     * Commands
     *
     * @inheritdoc
     */
    public function actionIndex()
    {
        echo 'yii user/view-all' . PHP_EOL;
        echo 'yii user/create' . PHP_EOL;
        echo 'yii user/remove' . PHP_EOL;
        echo 'yii user/change-password' . PHP_EOL;
    }

    /**
     * Create new User
     *
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new User();
        $this->readValue($model, 'username');
        $model->setPassword($this->prompt(Console::convertEncoding(Yii::t('app', 'Password')) . ':', [
            'required' => true,
            'pattern' => '#^.{4,255}$#i',
            'error' => Console::convertEncoding(Yii::t('app', 'More than {:number} symbols', [':number' => 4])),
        ]));
        $model->generateAuthKey();
        $this->log($model->save());
    }

    /**
     * Remove User
     *
     * @throws Exception
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionRemove()
    {
        $username = $this->prompt(Console::convertEncoding(Yii::t('app', 'Username')) . ':', ['required' => true]);
        $model = $this->findModel($username);
        if ($model->delete() !== false) {
            $this->log(true);
        } else {
            $this->log(false);
        }
    }

    /**
     * Change password to User
     *
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function actionChangePassword()
    {
        $username = $this->prompt(Console::convertEncoding(Yii::t('app', 'Username')) . ':', ['required' => true]);
        $model = $this->findModel($username);
        $model->setPassword($this->prompt(Console::convertEncoding(Yii::t('app', 'New password')) . ':', [
            'required' => true,
            'pattern' => '#^.{4,255}$#i',
            'error' => Console::convertEncoding(Yii::t('app', 'More than {:number} symbols', [':number' => 4])),
        ]));
        $this->log($model->save());
    }

    /**
     * View all users
     */
    public function actionViewAll()
    {
        if ($model = User::find()->all()) {
            foreach ($model as $item) {
                echo $item->username . PHP_EOL;
            }
        } else {
            echo Yii::t('app', 'Not found');
        }
    }

    /**
     * @param string $username
     * @throws \yii\console\Exception
     * @return User the loaded model
     */
    private function findModel($username)
    {
        if (!$model = User::findOne(['username' => $username])) {
            throw new Exception(
                Console::convertEncoding(
                    Yii::t('app', 'User "{:Username}" not found', [':Username' => $username])
                )
            );
        }
        return $model;
    }

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     */
    private function readValue($model = null, $attribute = '')
    {
        $model->$attribute = $this->prompt(Console::convertEncoding(Yii::t('app', mb_convert_case($attribute, MB_CASE_TITLE, 'UTF-8') . ':')), [
            'validator' => function ($input, &$error) use ($model, $attribute) {
                $model->$attribute = $input;
                if ($model->validate([$attribute])) {
                    return true;
                } else {
                    $error = Console::convertEncoding(implode(',', $model->getErrors($attribute)));
                    return false;
                }
            },
        ]);
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
