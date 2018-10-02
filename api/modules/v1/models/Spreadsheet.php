<?php

namespace api\modules\v1\models;

use Yii;
use yii\helpers\ArrayHelper;
use modules\spreadsheet\traits\ModuleTrait;

/**
 * Class Spreadsheet
 * @package api\modules\v1\models
 */
class Spreadsheet extends \modules\spreadsheet\models\Spreadsheet
{
    //use ModuleTrait;

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

    /**
     * /api/v1/message?expand=status
     * @return array
     */
    public function extraFields()
    {
        return [];
    }
}
