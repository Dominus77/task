<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use api\modules\v1\models\Spreadsheet;
use app\components\Rbac;

/**
 * Class SpreadsheetController
 * @package api\modules\v1\controllers
 */
class SpreadsheetController extends Controller
{
    /**
     * @var string
     */
    //public $modelClass = 'api\modules\v1\models\Spreadsheet';

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
    }

    /**
     * @return array
     */
    public function actionIndex()
    {
        $model = new Spreadsheet();
        return $model->getTables();
    }

}
