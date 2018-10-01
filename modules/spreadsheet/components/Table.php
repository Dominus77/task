<?php

namespace modules\spreadsheet\components;

use Yii;
use yii\helpers\Html;
use modules\spreadsheet\traits\ModuleTrait;
use app\components\Rbac;

/**
 * Class Table
 * @package modules\spreadsheet\components
 * @property array $itemsToMenu
 */
class Table
{
    use ModuleTrait;

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
                $items[$key]['url'] = ['/spreadsheet/' . $value . '/index'];
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
}
