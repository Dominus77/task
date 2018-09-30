<?php

namespace modules\spreadsheet\components;

use Yii;
use yii\helpers\FileHelper;
use modules\spreadsheet\traits\ModuleTrait;
use modules\spreadsheet\components\generator\GrudGenerator;

/**
 * Class ProcessModel
 * @package modules\spreadsheet\components
 */
class ProcessGrud
{
    use ModuleTrait;

    /**
     * Create model
     *
     * @param string $tableName
     * @return bool
     */
    public function createGrud($tableName)
    {
        $modelName = ucfirst($tableName);
        $model = new GrudGenerator();
        $model->modelClass = $this->getNsModel() . '\\' . $modelName;
        $model->searchModelClass = $this->getNsModel() . '\\search\\' . $modelName . 'Search';
        $model->controllerClass = $this->getNsController() . '\\' . $modelName . 'Controller';
        $model->viewPath = $this->getViewsPath($tableName);
        $model->generate();
        return true;
    }

    /**
     * Remove GRUD
     *
     * @param string $tableName
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function removeGrud($tableName)
    {
        $name = ucfirst($tableName);
        $controller = $this->getControllerPath() . '/' . $name . 'Controller.php';
        $modelSearch = $this->getSearchModelPath() . '/' . $name . 'Search.php';
        $view = Yii::getAlias($this->getViewsPath($tableName));
        $remove = [
            $controller,
            $modelSearch,
            $view,
        ];
        foreach ($remove as $item) {
            FileHelper::unlink($item);
        }
        return $this->removeDir($view);
    }

    /**
     * Remove Directory
     *
     * @param $dir
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function removeDir($dir)
    {
        if ($dir) {
            if ([] === (array_diff(scandir($dir), ['.', '..']))) {
                FileHelper::removeDirectory($dir);
            }
            return true;
        }
        return false;
    }
}
