<?php

namespace modules\spreadsheet\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use modules\spreadsheet\traits\ModuleTrait;

/**
 * Class Spreadsheet
 * @package modules\spreadsheet\models
 * @property array $tablesNames
 */
class Spreadsheet extends Model
{
    use ModuleTrait;

    public $tables;

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

    /**
     * Вывод название таблиц с пагинацией
     *
     * @return array
     */
    public function getTables()
    {
        $tables = $this->getTablesNames();
        $provider = new ArrayDataProvider([
            'allModels' => $tables,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $provider->getModels();
    }

    /**
     * Имена созданных таблиц
     *
     * @return array
     */
    public function getTablesNames()
    {
        $files = $this->getFilesNames();
        $tables = [];
        $db = Yii::$app->db;
        foreach ($files as $file => $name) {
            if ($db->getTableSchema($name, true) !== null) {
                $tables[]['tableName'] = $name;
            }
        }
        return $tables;
    }
}
