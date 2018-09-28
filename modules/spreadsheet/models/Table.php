<?php

namespace modules\spreadsheet\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use modules\spreadsheet\traits\ModuleTrait;
use app\components\Rbac;

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
     *
     * @return array
     */
    public function getItemsToMenu()
    {
        $files = $this->getFilesNames();
        $items = [];
        foreach ($files as $key => $value) {
            $items[$key]['label'] = Html::tag('span', 42, ['class' => 'badge pull-right']) . $value;
            $items[$key]['url'] = ['/spreadsheet/default/view', 'name' => $value];
            $items[$key]['visible'] = Yii::$app->user->can(Rbac::PERMISSION_ACCESS_TABLE);
        }
        return $items;
    }
}
