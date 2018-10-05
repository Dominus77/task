<?php

/**
 * Class SignupCest
 */
class SignupCest
{
    /**
     * @var string
     */
    protected $formId = '#form-signup';

    /**
     * @inheritdoc
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('site/signup');
    }

    /**
     * @inheritdoc
     * @param FunctionalTester $I
     */
    public function signupWithEmptyFields(FunctionalTester $I)
    {
        $I->see('Sign Up', 'h1');
        $I->see('Please fill in the following fields to sign up:');
        $I->submitForm($this->formId, []);
        $I->see('Username cannot be blank.');
        $I->see('Password cannot be blank.');

    }

    /**
     * @inheritdoc
     * @param FunctionalTester $I
     */
    public function signupWithWrong(FunctionalTester $I)
    {
        $I->submitForm(
            $this->formId, [
                'SignupForm[username]' => 'erau',
                'SignupForm[password]' => 'wrong',
            ]
        );
        $I->dontSee('Username cannot be blank.', '.help-block');
        $I->dontSee('Password cannot be blank.', '.help-block');
    }

    /**
     * @inheritdoc
     * @param FunctionalTester $I
     */
    public function signupSuccessfully(FunctionalTester $I)
    {
        $I->submitForm($this->formId, [
            'SignupForm[username]' => 'testers',
            'SignupForm[email]' => 'testers@example.com',
            'SignupForm[password]' => '123456',
        ]);

        $I->see('It remains to activate the account, check your mail.');
    }
}
