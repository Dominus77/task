<?php

/**
 * @var $this yii\web\View
 * @var $model modules\spreadsheet\models\Table
 * @var $import modules\spreadsheet\components\Import
 */

use yii\helpers\Html;
use modules\spreadsheet\widgets\MenuWidgets;

$this->title = Yii::t('app', 'Tables');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spreadsheet-default-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-3">
            <?= MenuWidgets::widget() ?>
        </div>
        <div class="col-md-9">
            <?php
            $files = $import->getFiles();
            $parse = $import->parseFile($files[0]);
            $import->loadDataDbTable($files[0]);
            \yii\helpers\VarDumper::dump($parse, 10, 1);
            ?>
        </div>
    </div>
</div>
