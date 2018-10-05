<?php

/**
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model app\models\SignupForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Sign Up');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Please fill in the following fields to sign up'); ?>:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'form-signup',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textInput([
        'placeholder' => true
    ]) ?>

    <?= $form->field($model, 'email')->textInput([
        'placeholder' => true
    ]) ?>

    <?= $form->field($model, 'password')->passwordInput([
        'placeholder' => true
    ]) ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton(Yii::t('app', 'Sign Up'), [
                'class' => 'btn btn-success',
                'name' => 'signup-button'
            ]) ?>
            <?= Html::a(Yii::t('app', 'Login'), ['site/login'], [
                'class' => 'btn btn-primary'
            ]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
