<?php

namespace app\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;

/**
 * Class EmailConfirmForm
 * @package app\models
 */
class EmailConfirmForm extends Model
{
    /**
     * @var \app\models\User|bool
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param  mixed $token
     * @param  array $config
     * @throws \yii\base\InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token = '', $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException(Yii::t('app', 'Email confirm token cannot be blank.'));
        }
        $this->_user = User::findByEmailConfirmToken($token);
        if (!$this->_user) {
            throw new InvalidArgumentException(Yii::t('app', 'Wrong Email confirm token.'));
        }
        parent::__construct($config);
    }

    /**
     * Confirm email.
     *
     * @return bool|\yii\rbac\Assignment if email was confirmed.
     * @throws \Exception
     */
    public function confirmEmail()
    {
        $user = $this->_user;
        $user->status = User::STATUS_ACTIVE;
        $user->removeEmailConfirmToken();
        if ($user->save(false)) {
            return true;
        }
        return false;
    }
}
