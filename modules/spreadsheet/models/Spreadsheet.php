<?php

namespace modules\spreadsheet\models;

use Yii;
use yii\base\Model;

/**
 * Class Spreadsheet
 * @package modules\spreadsheet\models
 */
class Spreadsheet extends Model
{
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [];
    }


    public function getTables()
    {

    }
}
