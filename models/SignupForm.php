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
            ['username', 'match', 'pattern' => '#^[\w_-]+$#i'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => Yii::t('app', 'This username is already taken.')],
            ['username', 'string', 'min' => self::LENGTH_STRING_USERNAME_MIN, 'max' => self::LENGTH_STRING_USERNAME_MAX],

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
            $user = $this->loadModel();
            if ($user->save()) {
                return $user;
            }
        }
        return null;
    }

    /**
     * @return User
     * @throws \yii\base\Exception
     */
    protected function loadModel()
    {
        $user = new User();
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        return $user;
    }
}
