<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

/**
 * Class UserController
 * @package api\modules\v1\controllers
 */
class UserController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'api\modules\v1\models\User';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];

        // header('Authorization: Bearer FFFF70it7tzNsHddEiq0BZ0i-OU8S3xV');
        $behaviors['authenticator']['class'] = HttpBearerAuth::class;
        $behaviors['authenticator']['only'] = ['update'];

        return $behaviors;
    }
}
