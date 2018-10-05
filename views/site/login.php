<?php

/**
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * $model app\models\LoginForm
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Please fill out the following fields to login') ?>:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>\n<div class=\"col-lg-offset-1 col-lg-11\">{hint}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textInput([
        'placeholder' => Yii::t('app', 'Username or Email'),
    ]) ?>

    <?= $form->field($model, 'password')->passwordInput([
        'placeholder' => true
    ])->hint(Yii::t('app', 'If you have forgotten your password, use {:Link}',
            [
                ':Link' => Html::a(Yii::t('app', 'form of password reset'), ['request-password-reset'])
            ]
        ) . '.'
    ) ?>

    <?= $form->field($model, 'rememberMe')->checkbox([
        'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ]) ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton(Yii::t('app', 'Login'), [
                'class' => 'btn btn-success',
                'name' => 'login-button'
            ]) ?>

            <?= Html::a(Yii::t('app', 'Sign Up'), ['site/signup'], [
                'class' => 'btn btn-primary'
            ]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
