<?php

namespace modules\spreadsheet\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\Rbac;
use modules\spreadsheet\components\Table;

/**
 * Class DefaultController
 * @package modules\spreadsheet\controllers
 */
class DefaultController extends Controller
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
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => [Rbac::PERMISSION_ACCESS_TABLE],
                    ],
                    [
                        'allow' => true,
                        'roles' => [Rbac::PERMISSION_EDIT_TABLE],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new Table();
        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
