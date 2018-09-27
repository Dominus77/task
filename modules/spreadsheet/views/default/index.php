<?php

/**
 * @var $this yii\web\View
 * @var $model modules\spreadsheet\components\Import
 */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Tables');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spreadsheet-default-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        //$model->createDbTable();
        //\yii\helpers\VarDumper::dump($model->getAllTables(), 10, 1);
        //\yii\helpers\VarDumper::dump($model->createColumns(), 10, 1);
        $files = $model->getFiles();
        $model->parseFile($files[0]);
        $columns = $model->getColumns();
        \yii\helpers\VarDumper::dump($columns, 10, 1);
        ?>
    </p>
</div>
