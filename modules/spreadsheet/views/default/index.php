<?php

/**
 * @var $this yii\web\View
 * @var $model modules\spreadsheet\models\Table
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use yii\helpers\Html;
use yii\widgets\Menu;

$this->title = Yii::t('app', 'Tables');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spreadsheet-default-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-3">
            <?= Menu::widget([
                'encodeLabels' => false,
                'options' => [
                    'class' => 'nav nav-pills nav-stacked',
                ],
                'items' => $model->getItemsToMenu(),
            ]);
            ?>
        </div>
        <div class="col-md-9">
            <?php
            \yii\helpers\VarDumper::dump($model->getFilesNames(), 10, 1);
            ?>
        </div>
    </div>
</div>
