<?php

namespace modules\spreadsheet\widgets;

use yii\bootstrap\Widget;
use yii\widgets\Menu;
use modules\spreadsheet\components\Table;

/**
 * Class MenuWidgets
 * @package modules\spreadsheet\widgets
 * @property array $items
 */
class MenuWidgets extends Widget
{
    /**
     * @var bool
     */
    public $status = true;

    /**
     * Запуск выджета
     *
     * @return string|void
     * @throws \yii\db\Exception
     */
    public function run()
    {
        if (($this->status === true)) {
            if ($items = $this->getItems()) {
                echo Menu::widget([
                    'encodeLabels' => false,
                    'options' => [
                        'class' => 'nav nav-pills nav-stacked',
                    ],
                    'items' => $items,
                ]);
            }
        }
    }

    /**
     * Пункты меню
     *
     * @return array
     * @throws \yii\db\Exception
     */
    protected function getItems()
    {
        $model = new Table();
        return $model->getItemsToMenu();
    }
}
