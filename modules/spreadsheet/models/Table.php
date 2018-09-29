<?php

namespace modules\spreadsheet\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use modules\spreadsheet\traits\ModuleTrait;
use app\components\Rbac;
use yii\base\Model;
use yii\db\Query;

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
     *
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
     * Вывод таблицы
     *
     * @return null|ActiveDataProvider
     */
    public function getActiveProvider()
    {
        if ($this->tableName) {
            $query = new Query;
            $query->from($this->tableName);
            $provider = new ActiveDataProvider([
                'query' => $query,
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
     * Вывод данных таблицы
     *
     * @param integer $id
     * @return array|bool|null
     */
    public function getViewModel($id)
    {
        if ($this->tableName) {
            $query = new Query;
            $model = $query->from($this->tableName)
                ->where(['id' => $id])
                ->one();
            return $model;
        }
        return null;
    }
}
