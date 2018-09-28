<?php

namespace modules\spreadsheet\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use modules\spreadsheet\traits\ModuleTrait;
use app\components\Rbac;
use yii\helpers\VarDumper;

/**
 * Class Table
 * @package modules\spreadsheet\models
 *
 * @property array $allTables
 * @property array $itemsToMenu
 */
class Table extends \yii\db\ActiveRecord
{
    use ModuleTrait;

    public $name;

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

    /**
     * Пункты названия таблиц для меню
     * Если таблица отсутствует, пункт не выводится.
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function getItemsToMenu()
    {
        $files = $this->getFilesNames();
        $items = [];
        foreach ($files as $key => $value) {
            $count = $this->getCountItemsTable($value);
            if (($count !== false) && (int)$count >= 0) {
                $items[$key]['label'] = Html::tag('span', $count, ['class' => 'badge pull-right']) . $value;
                $items[$key]['url'] = ['/spreadsheet/default/show', 'name' => $value];
                $items[$key]['visible'] = Yii::$app->user->can(Rbac::PERMISSION_ACCESS_TABLE);
            }
        }
        return $items;
    }

    /**
     * Получаем количество записей
     *
     * @param string $table_name
     * @return bool|\yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public function getCountItemsTable($table_name = '')
    {
        if ($table_name) {
            $db = Yii::$app->db;
            if ($db->getTableSchema($table_name, true) !== null) {
                $command = $db->createCommand('SELECT COUNT(*) FROM ' . $table_name . ';');
                $result = $command->queryAll();
                return $result[0]['COUNT(*)'];
            }
        }
        return false;
    }
}
