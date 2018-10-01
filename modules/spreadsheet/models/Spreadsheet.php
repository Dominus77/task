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
     * @var string
     */
    public $tables = 'OK!';

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['tables'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'tables' => Yii::t('app', 'Tables'),
        ];
    }

    /**
     * @return string
     */
    public function getTables()
    {
        return $this->tables;
    }
}
