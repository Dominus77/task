<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use api\modules\v1\models\SignupForm;
use api\modules\v1\models\EmailConfirmForm;
use api\modules\v1\models\User;
use app\components\Rbac;

/**
 * Class UserController
 * @package api\modules\v1\controllers
 */
class UserController extends ActiveController
{
    /**
     * @var \yii\db\ActiveRecord
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

        // header('Authorization: Bearer 51aGMh6_TJDKC9dpZPBaE23TX5NXruI3');
        $behaviors['authenticator']['class'] = HttpBearerAuth::class;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'signup', 'email-confirm'];

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
            if (!Yii::$app->user->can(Rbac::PERMISSION_VIEW_USER))
                throw new \yii\web\ForbiddenHttpException(sprintf('Access is denied.', $action));
        }
        if ($action === 'view') {
            if (!Yii::$app->user->can(Rbac::PERMISSION_VIEW_USER))
                throw new \yii\web\ForbiddenHttpException(sprintf('Access is denied.', $action));
        }
        if ($action === 'update' || $action === 'create') {
            if (!Yii::$app->user->can(Rbac::PERMISSION_UPDATE_USER))
                throw new \yii\web\ForbiddenHttpException(sprintf('Access is denied.', $action));
        }
        if ($action === 'delete') {
            if (!Yii::$app->user->can(Rbac::PERMISSION_DELETE_USER))
                throw new \yii\web\ForbiddenHttpException(sprintf('Access is denied.', $action));
        }
    }

    /**
     * Регистрация
     *
     * POST /api/v1/users/signup
     *
     * body
     * username=USERNAME
     * email=EMAIL
     * password=PASSWORD
     *
     * @return array
     * @throws \yii\base\Exception
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->bodyParams, '') && $model->validate()) {
            if ($user = $model->signup()) {
                return [
                    'status' => true,
                    'result' => Yii::t('app', 'It remains to activate the account, check your mail.')
                ];
            }
        }
        return ['status' => false, 'result' => $model->getErrors()];
    }

    /**
     * Профиль
     *
     * GET /api/v1/users/profile
     * GET /api/v1/users/profile?expand=role
     *
     * @return \yii\data\ActiveDataProvider
     */
    public function actionProfile()
    {
        /** @var \api\modules\v1\models\User $identity */
        $identity = Yii::$app->user->identity;
        $id = $identity->id;
        return new \yii\data\ActiveDataProvider([
            'query' => $this->modelClass::find()->where(['id' => $id, 'status' => User::STATUS_ACTIVE]),
        ]);
    }

    /**
     * Confirm Email
     *
     * GET /api/v1/users/email-confirm?token=EMAIL_CONFIRM_TOKEN
     *
     * @param string $token
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionEmailConfirm($token)
    {
        try {
            $model = new EmailConfirmForm($token);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->confirmEmail()) {
            return [
                'status' => true,
                'result' => Yii::t('app', 'Thank you for registering! Now you can log in using your credentials.'),
            ];
        }
        return [
            'status' => true,
            'result' => Yii::t('app', 'Error sending message!'),
        ];
    }
}
