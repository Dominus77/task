<?php

namespace api\modules\v1\controllers;

use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

/**
 * API Base Controller
 * All controllers within API app must extend this controller!
 */
class ApiController extends \yii\rest\ActiveController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Add CORS filter
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
        ];

        // header('Authorization: Bearer FFFF70it7tzNsHddEiq0BZ0i-OU8S3xV');
        $behaviors['authenticator']['class'] = HttpBearerAuth::class;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }
}
