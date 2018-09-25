<?php

namespace tests\models;

use app\fixtures\User as UserFixture;
use app\models\LoginForm;

/**
 * Class LoginFormTest
 * @package tests\models
 */
class LoginFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \_generated\UnitTesterActions
     */
    protected $tester;

    private $model;

    /**
     * @inheritdoc
     */
    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ]
        ]);
    }

    protected function _after()
    {
        \Yii::$app->user->logout();
    }

    public function testLoginNoUser()
    {
        $this->model = new LoginForm([
            'username' => 'not_existing_username',
            'password' => 'not_existing_password',
        ]);

        expect_not($this->model->login());
        expect_that(\Yii::$app->user->isGuest);
    }

    public function testLoginWrongPassword()
    {
        $this->model = new LoginForm([
            'username' => 'demo',
            'password' => 'wrong_password',
        ]);

        expect_not($this->model->login());
        expect_that(\Yii::$app->user->isGuest);
        expect($this->model->errors)->hasKey('password');
    }

    public function testLoginCorrect()
    {
        $this->model = new LoginForm([
            'username' => 'tester',
            'password' => 'password_0',
        ]);

        expect_that($this->model->login());
        expect_not(\Yii::$app->user->isGuest);
        expect($this->model->errors)->hasntKey('password_hash');
    }
}
