<?php

/**
 * @var $this yii\web\View
 * @var $model modules\spreadsheet\models\Table
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $name string
 */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use modules\spreadsheet\widgets\MenuWidgets;

$this->title = Yii::t('app', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($name), 'url' => ['/spreadsheet/default/show', 'name' => $name]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spreadsheet-default-view">

    <h1><?= Html::encode($this->title) ?>: "<?= Html::encode($name); ?>"</h1>

    <div class="row">
        <div class="col-md-3">
            <?= MenuWidgets::widget() ?>
        </div>
        <div class="col-md-9">
            Туту данные
        </div>
    </div>
</div>
