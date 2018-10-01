<?php

namespace api\modules\v1\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Spreadsheet
 * @package api\modules\v1\models
 */
class Spreadsheet extends \modules\spreadsheet\models\Spreadsheet
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), []);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), []);
    }

    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'tables'
        ];
    }
}
