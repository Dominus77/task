<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use app\models\User;
use yii\web\NotFoundHttpException;

/**
 * Class ProfileController
 * @package app\controllers
 */
class ProfileController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'generate-auth-key' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays user profile.
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $model = $this->findModel();
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Action Generate new auth key
     *
     * @return array|Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionGenerateAuthKey()
    {
        $model = $this->processGenerateAuthKey();
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'success' => $model->auth_key,
            ];
        }
        return $this->redirect(['index']);
    }

    /**
     * Generate new auth key
     *
     * @return User|null
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    private function processGenerateAuthKey()
    {
        $model = $this->findModel();
        $model->generateAuthKey();
        $model->save();
        return $model;
    }

    /**
     * @return User|null
     * @throws NotFoundHttpException
     */
    private function findModel()
    {
        if (!Yii::$app->user->isGuest) {
            /** @var User $identity */
            $identity = Yii::$app->user->identity;
            if (($model = User::findOne($identity->id)) !== null) {
                return $model;
            }
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
