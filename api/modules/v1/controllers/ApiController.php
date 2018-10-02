<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use app\components\Rbac;

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

    /**
     * Проверяет права текущего пользователя.
     *
     * Этот метод должен быть переопределен, чтобы проверить, имеет ли текущий пользователь
     * право выполнения указанного действия над указанной моделью данных.
     * Если у пользователя нет доступа, следует выбросить исключение [[ForbiddenHttpException]].
     *
     * @param string $action ID действия, которое надо выполнить
     * @param null $model модель, к которой нужно получить доступ. Если `null`, это означает, что модель, к которой нужно получить доступ, отсутствует.
     * @param array $params дополнительные параметры
     * @throws \yii\web\ForbiddenHttpException если у пользователя нет доступа
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action === 'index') {
            if (!Yii::$app->user->can(Rbac::PERMISSION_ACCESS_TABLE))
                throw new \yii\web\ForbiddenHttpException(sprintf('You can only %s articles that you\'ve created.', $action));
        }
        if ($action === 'view') {
            if (!Yii::$app->user->can(Rbac::PERMISSION_VIEW_TABLE))
                throw new \yii\web\ForbiddenHttpException(sprintf('You can only %s articles that you\'ve created.', $action));
        }
        if ($action === 'update' || $action === 'delete' || $action === 'create') {
            if (!Yii::$app->user->can(Rbac::PERMISSION_EDIT_TABLE))
                throw new \yii\web\ForbiddenHttpException(sprintf('You can only %s articles that you\'ve created.', $action));
        }
    }

    /**
     * Переопределение actions
     *
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();

        // отключить действия "delete" и "create"
        unset($actions['delete'], $actions['create']);

        // настроить подготовку провайдера данных с помощью метода "prepareDataProvider()"
        //$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    /**
     * Подготовить и вернуть провайдер данных для действия "index"
     */
    public function prepareDataProvider()
    {

    }
}
