<?php

namespace modules\spreadsheet\models;

use Yii;
use modules\spreadsheet\traits\ModuleTrait;

/**
 * Class Table
 * @package modules\spreadsheet\models
 *
 * @property array $allTables
 */
class Table extends \yii\db\ActiveRecord
{
    use ModuleTrait;

    /**
     * {@inheritdoc}
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * Show all tables
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function getAllTables()
    {
        $db = Yii::$app->db;
        $query = $db->createCommand('SHOW TABLES;');
        $result = $query->queryAll();
        return $result;
    }
}
