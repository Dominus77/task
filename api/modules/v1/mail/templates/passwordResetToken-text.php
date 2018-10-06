<?php

/**
 * @var $this yii\web\View
 * @var $user app\models\User
 */

$urlManager = Yii::$app->urlManager;
$resetLink = $urlManager->createAbsoluteUrl(['v1/users/reset-password', 'token' => $user->password_reset_token]);
?>

<?= Yii::t('app', 'Hello {username}', ['username' => $user->username]); ?>!

<?= Yii::t('app', 'You or someone else indicated your email address in the form of a password reset on the {:Website}. If you did not then just ignore this email.', [':Website' => Yii::$app->name]) ?>

<?= Yii::t('app', 'To reset your password follow the link below, or copy it to your browser.') ?>

<?= $resetLink ?>

<?= Yii::t('app', 'The letter was sent by the robot and you do not need to respond to it.') ?>


<?= Yii::t('app', 'Sincerely, website administration {:Website}', [':Website' => Yii::$app->name]) ?>
