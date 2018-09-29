<?php

namespace modules\spreadsheet\models;

use Yii;
use yii\helpers\Html;
use modules\spreadsheet\traits\ModuleTrait;
use app\components\Rbac;
use yii\base\Model;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\VarDumper;

/**
 * Class Table
 * @package modules\spreadsheet\models
 *
 * @property array $allTables
 * @property array $itemsToMenu
 */
class Table extends Model
{
    use ModuleTrait;

    public $tableName;

    public function init()
    {
        parent::init();
    }

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
     * TODO: временная функция
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
            if (($count !== false) && $count >= 0) {
                $items[$key]['label'] = Html::tag('span', $count, ['class' => 'badge pull-right']) . $value;
                $items[$key]['url'] = ['/spreadsheet/default/show', 'name' => $value];
                $items[$key]['visible'] = Yii::$app->user->can(Rbac::PERMISSION_VIEW_TABLE);
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
                $result = $db->createCommand('select count(*) from ' . $table_name)->queryScalar();
                return $result;
            }
        }
        return false;
    }

    /**
     * Атрибуты
     * @return array
     */
    public function getFieldsTable()
    {
        $fields = [];
        if ($this->tableName) {
            $db = Yii::$app->db;
            $columns = $db->getTableSchema($this->tableName)->columns;
            foreach ($columns as $item) {
                $fields[] = $item->name;
            }
        }
        return $fields;
    }

    /**
     * @return null|ArrayDataProvider
     * @throws \yii\db\Exception
     */
    public function getDataProviderArray()
    {
        if ($this->tableName) {
            $db = Yii::$app->db;
            $models = $db->createCommand('SELECT * FROM ' . $this->tableName)->queryAll();
            $provider = new ArrayDataProvider([
                'allModels' => $models,
                'pagination' => [
                    'pageSize' => 5,
                ],
                'sort' => [
                    'attributes' => $this->getFieldsTable(),
                ],
            ]);
            return $provider;
        }
        return null;
    }

    /**
     * @return null|ActiveDataProvider
     */
    public function getDataTable()
    {
        if ($this->tableName) {
            $db = Yii::$app->db;
            if ($db->getTableSchema($this->tableName, true) !== null) {
                $query = (new Query())->from($this->tableName);
                $provider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [
                        'pageSize' => 5,
                    ],
                ]);
                return $provider;
            }
        }
        return null;
    }
}
