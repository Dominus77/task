<?php

namespace modules\spreadsheet\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\Rbac;
use modules\spreadsheet\models\Table;

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
                        'actions' => ['show', 'view'],
                        'allow' => true,
                        'roles' => [Rbac::PERMISSION_VIEW_TABLE],
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

    /**
     * Вывод таблицы
     *
     * @param string $name
     * @return string
     */
    public function actionShow($name)
    {
        $model = $this->findModel($name);
        return $this->render('show', [
            'model' => $model,
        ]);
    }

    /**
     * Вывод данных таблицы
     *
     * @param string $name
     * @return string
     */
    public function actionView($name)
    {
        $model = $this->findModel($name);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * @param string $name
     * @return Table
     */
    protected function findModel($name)
    {
        return new Table();
    }
}
