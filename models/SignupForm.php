<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class SignupForm
 * @package app\models
 *
 * @property string $username Username
 * @property string $email Email
 * @property string $password Password
 */
class SignupForm extends Model
{
    const LENGTH_STRING_USERNAME_MIN = 2;
    const LENGTH_STRING_USERNAME_MAX = 255;

    const LENGTH_STRING_PASSWORD_MIN = 6;
    const LENGTH_STRING_PASSWORD_MAX = 16;

    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#i', 'message' => Yii::t('app', 'It is allowed to use the Latin alphabet, dashes and underscores.')],
            ['username', 'unique', 'targetClass' => User::class, 'message' => Yii::t('app', 'This username is already taken.')],
            ['username', 'string', 'min' => self::LENGTH_STRING_USERNAME_MIN, 'max' => self::LENGTH_STRING_USERNAME_MAX],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => Yii::t('app', 'This email already exists.')],

            ['password', 'required'],
            ['password', 'string', 'min' => self::LENGTH_STRING_PASSWORD_MIN, 'max' => self::LENGTH_STRING_PASSWORD_MAX],
        ];
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
        ];
    }

    /**
     * Signs users up.
     *
     * @return User|null the saved model or null if saving fails
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = $this->processLoadModel();
            if ($user->save()) {
                Yii::$app->mailer->compose(
                    $this->getTemplate(),
                    ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                    ->setTo($this->email)
                    ->setSubject(Yii::$app->name . ' | ' . Yii::t('app', 'Account activation'))
                    ->send();
                return $user;
            }
        }
        return null;
    }

    /**
     * Template mail
     *
     * @return array
     */
    public function getTemplate()
    {
        return [
            'html' => '@app/mail/templates/emailConfirm-html',
            'text' => '@app/mail/templates/emailConfirm-text'
        ];
    }

    /**
     * @return User
     * @throws \yii\base\Exception
     */
    protected function processLoadModel()
    {
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->status = User::STATUS_WAIT;
        $user->generateAuthKey();
        $user->generateEmailConfirmToken();
        return $user;
    }
}
