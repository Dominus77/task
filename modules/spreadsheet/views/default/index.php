<?php

/**
 * @var $this yii\web\View
 * @var $model \modules\spreadsheet\components\Table
 */

use yii\helpers\Html;
use modules\spreadsheet\widgets\MenuWidgets;

$this->title = Yii::t('app', 'Tables');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spreadsheet-default-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-md-12">
            <?php if (empty($model->getItemsToMenu())) : ?>
                <?= $this->render('_empty') ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= MenuWidgets::widget() ?>
        </div>
        <div class="col-md-9">
            <?php if (!empty($model->getItemsToMenu())) : ?>
                <?= $this->render('_table') ?>
            <?php endif; ?>
        </div>
    </div>
    <?php
    $tableName = 'test';
    $db = Yii::$app->db;
    $result = $db->getTableSchema($tableName, true);

    $table = new \modules\spreadsheet\components\Table();
    $countRows = $table->getCountItemsTable($tableName);
    $strColumns = implode(' | ', $result->getColumnNames());
    $length = mb_strlen($strColumns);

    $decor = '';
    for ($i = 0; $i <= $length; $i++) {
        $decor .= 'x';
    }

    $decor = nl2br($decor . PHP_EOL);

    echo $decor;
    echo nl2br($strColumns . PHP_EOL);
    echo nl2br($decor . PHP_EOL);
    echo nl2br($countRows);
    //\yii\helpers\VarDumper::dump($columns, 10, 1);
    //\yii\helpers\VarDumper::dump($length, 10, 1);
    ?>
</div>
