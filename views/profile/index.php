<?php

/**
 * @var yii\web\View $this
 * @var app\models\User $identity
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('app', 'Profile');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'password_hash',
            [
                'attribute' => 'auth_key',
                'format' => 'raw',
                'value' => function ($model) {
                    $key = Html::tag('code', $model->auth_key, ['id' => 'authKey']);
                    $link = Html::a(Yii::t('app', 'Generate'), ['profile/generate-auth-key'], [
                        'class' => 'btn btn-sm btn-default pull-right',
                        'title' => Yii::t('app', 'Generate new key'),
                        'onclick' => "                                
                            $.ajax({
                                type: 'POST',
                                cache: false,
                                url: this.href,
                                success: function(response) {                                       
                                    if(response.success) {
                                        $('#authKey').html(response.success);
                                    }
                                }
                            });
                            return false;
                        ",
                    ]);
                    return $key . ' ' . $link;
                }
            ],
            'role',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]); ?>
</div>
