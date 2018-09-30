<?php

namespace modules\spreadsheet\components;

use Yii;
use yii\helpers\Html;
use modules\spreadsheet\traits\ModuleTrait;
use app\components\Rbac;
use modules\spreadsheet\components\generator\ApiModelGenerator;
use modules\spreadsheet\components\generator\ApiControllerGenerator;
use yii\helpers\FileHelper;

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


    public function getCreateModelApi()
    {
        $tableName = 'test';
        $generator = new ApiModelGenerator();
        $generator->tableName = $tableName;
        $generator->modelClass = ucfirst($tableName);
        $generator->ns = $this->getNsApiModel();
        $generator->generate();
        return true;
    }

    public function removeModelApi()
    {
        $tableName = 'test';
        $path = $this->getApiModelPath() . '/' . ucfirst($tableName) . '.php';
        FileHelper::unlink($path);
        if (!file_exists($path))
            return true;
        return false;
    }

    public function getCreateControllerApi()
    {
        $tableName = 'test';
        $generator = new ApiControllerGenerator();
        $generator->modelClass = ucfirst($tableName);
        $generator->controllerClass = $this->getNsApiController().'\\'.ucfirst($tableName).'Controller';
        $generator->generate();
        return true;
    }

    public function removeControllerApi()
    {
        $tableName = 'test';
        $path = $this->getApiControllerPath() . '/' . ucfirst($tableName) . 'Controller.php';
        FileHelper::unlink($path);
        if (!file_exists($path))
            return true;
        return false;
    }
}
