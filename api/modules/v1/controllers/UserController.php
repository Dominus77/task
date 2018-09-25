<?php

namespace api\modules\v1\controllers;

use Yii;
use api\modules\v1\models\User;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
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

        /*$behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'only' => ['update'],
            'authMethods' => [
                'bearerAuth' => [
                    'class' => HttpBearerAuth::class,
                ],
            ]
        ];*/
        return $behaviors;
    }
}
