<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class PasswordResetRequestForm
 * @package app\models
 *
 * @property string $email Email
 * @property User $user
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::class,
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Yii::t('app', 'There is no users with this e-mail.'),
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {

        if ($user = $this->getUser()) {
            return Yii::$app->mailer->compose(
                ['html' => '@app/mail/templates/passwordResetToken-html', 'text' => '@app/mail/templates/passwordResetToken-text'],
                ['user' => $user])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                ->setTo($this->email)
                ->setSubject(Yii::$app->name . ' | ' . Yii::t('app', 'Access recovery'))
                ->send();
        }
        return false;
    }

    /**
     * @return bool|User
     */
    private function getUser()
    {
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user === null) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }
        return $user;
    }
}
