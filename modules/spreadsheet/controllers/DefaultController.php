<?php

namespace modules\spreadsheet\controllers;

use yii\web\Controller;
use modules\spreadsheet\components\Import;

/**
 * Class DefaultController
 * @package modules\spreadsheet\controllers
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = new Import();
        return $this->render('index', [
            'model' => $model
        ]);
    }
}
