<?php

namespace modules\spreadsheet\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\Rbac;
use modules\spreadsheet\models\Table;
use modules\spreadsheet\components\Import;

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
        $import = new Import();
        return $this->render('index', [
            'model' => $model,
            'import' => $import,
        ]);
    }

    /**
     * Вывод таблицы
     *
     * @param string $name
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionShow($name)
    {
        $model = $this->findModel($name);
        $dataProvider = $model->getDataProviderArray();
        return $this->render('show', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'name' => $name,
        ]);
    }

    /**
     * Просмотр данных таблицы
     *
     * @param string $name
     * @param integer $id
     * @return string
     */
    public function actionView($name, $id)
    {
        $model = $this->findModel($name);
        return $this->render('view', [
            'model' => $model,
            'name' => $name,
        ]);
    }

    /**
     * @param string $name
     * @param integer $id
     * @return Table
     */
    protected function findModel($name, $id = 0)
    {
        $model = new Table(['tableName' => $name]);
        return $model;
    }
}
