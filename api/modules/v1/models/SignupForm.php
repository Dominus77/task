<?php

namespace api\modules\v1\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class SignupForm
 * @package api\modules\v1\models
 */
class SignupForm extends \app\models\SignupForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), []);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), []);
    }
}
