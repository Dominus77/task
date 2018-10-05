<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id ID
 * @property string $username Username
 * @property string $password_hash Hash Password
 * @property string $auth_key Authorization Key
 * @property int $created_at Created
 * @property int $updated_at Updated
 * @property string $password_reset_token Password Token
 * @property string $email_confirm_token Email Confirm Token
 * @property string $email Email
 * @property int|string $status Status
 * @property int $last_visit Last Visit
 * @property string $authKey
 * @property string $password
 * @property string $role
 * @property array statusesArray Array statuses
 * @property string $statusName
 * @property string $statusLabelName
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * Users Statuses
     */
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_WAIT = 2;
    const STATUS_DELETED = 3;

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#i'],
            ['username', 'unique', 'targetClass' => self::class, 'message' => Yii::t('app', 'This username is already taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => self::class, 'message' => Yii::t('app', 'This email is already taken.')],
            ['email', 'string', 'max' => 255],

            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_WAIT],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'password_hash' => Yii::t('app', 'Hash Password'),
            'auth_key' => Yii::t('app', 'Authorization Key'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Updated'),
            'role' => Yii::t('app', 'Role'),
            'last_visit' => Yii::t('app', 'Last Visit'),
            'status' => Yii::t('app', 'Status'),
            'email_confirm_token' => Yii::t('app', 'Email Confirm Token'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username or email
     *
     * @param $string
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findByUsernameOrEmail($string)
    {
        return static::find()
            ->where(['or', ['username' => $string], ['email' => $string]])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->one();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Generates "remember me" authentication key
     *
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Finds user by password reset token
     *
     * @param mixed $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Generates email confirmation token
     */
    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    /**
     * @param mixed $email_confirm_token
     * @return bool|null|static
     */
    public static function findByEmailConfirmToken($email_confirm_token)
    {
        return static::findOne([
            'email_confirm_token' => $email_confirm_token,
            'status' => self::STATUS_WAIT
        ]);
    }

    /**
     * Removes email confirmation token
     */
    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param mixed $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['users.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * Присвоенная роль
     */
    public function getRole()
    {
        return ($role = $this->getUserRoleValue($this->id)) ? Yii::t('app', $role->description) : Yii::t('app', 'Not assigned');
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
                    return $value;
                }
                return null;
            });
        }
        return null;
    }

    /**
     * @return array
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_BLOCKED => Yii::t('app', 'Blocked'),
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_WAIT => Yii::t('app', 'Wait'),
            self::STATUS_DELETED => Yii::t('app', 'Deleted'),
        ];
    }

    /**
     * @return mixed
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }

    /**
     * @return array
     */
    public static function getLabelsArray()
    {
        return [
            self::STATUS_BLOCKED => 'default',
            self::STATUS_ACTIVE => 'success',
            self::STATUS_WAIT => 'warning',
            self::STATUS_DELETED => 'danger',
        ];
    }

    /**
     * Return <span class="label label-success">Active</span>
     * @return string
     */
    public function getStatusLabelName()
    {
        $name = ArrayHelper::getValue(self::getLabelsArray(), $this->status);
        return Html::tag('span', $this->getStatusName(), ['class' => 'label label-' . $name]);
    }

    /**
     * Set Status
     *
     * @return int|string
     */
    public function setStatus()
    {
        switch ($this->status) {
            case self::STATUS_ACTIVE:
                $this->status = self::STATUS_BLOCKED;
                break;
            case self::STATUS_DELETED:
                $this->status = self::STATUS_WAIT;
                break;
            default:
                $this->status = self::STATUS_ACTIVE;
        }
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->status === self::STATUS_DELETED;
    }
}
