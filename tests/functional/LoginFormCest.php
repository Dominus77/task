<?php

use app\fixtures\User as UserFixture;

/**
 * Class LoginFormCest
 */
class LoginFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ]
        ]);
        $I->amOnRoute('site/login');
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->see('Login', 'h1');

    }

    /**
     * @param string $username
     * @param string $password
     * @return array
     */
    protected function formParams($username, $password)
    {
        return [
            'LoginForm[username]' => $username,
            'LoginForm[password]' => $password,
        ];
    }

    /**
     * @inheritdoc
     * @param FunctionalTester $I
     */
    public function checkEmpty(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', $this->formParams('', ''));
        $I->see('Username cannot be blank.');
        $I->see('Password cannot be blank.');
    }

    /**
     * @inheritdoc
     * @param FunctionalTester $I
     */
    public function checkWrongPassword(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', $this->formParams('erau', 'wrong'));
        $I->see('Incorrect username or password.');
    }

    /**
     * @inheritdoc
     * @param FunctionalTester $I
     */
    public function checkValidLogin(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', $this->formParams('tester', 'password_0'));
        $I->see('Menu (tester)');
        $I->see('Profile');
        $I->dontSeeLink('Login');
        $I->dontSeeLink('Signup');
    }
}