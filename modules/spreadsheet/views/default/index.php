<?php

/**
 * @var $this yii\web\View
 * @var $model modules\spreadsheet\models\Table
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
            \yii\helpers\VarDumper::dump($model->getFilesNames(), 10, 1);
            ?>
        </div>
    </div>
</div>
