<?php

namespace api\modules\v1\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class User
 * @package api\modules\v1\models
 */
class User extends \app\models\User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), []);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), []);
    }

    /**
     * /api/v1/users
     * @return array
     */
    public function fields()
    {
        return [
            'id',
            'username',
            'email',
        ];
    }

    /**
     * /api/v1/users?expand=created_at
     * @return array
     */
    public function extraFields()
    {
        return [
            'created_at',
            'updated_at',
            'auth_key',
            'role',
            'status',
            'last_visit',
            'email_confirm_token',
            'password_reset_token',
        ];
    }
}
