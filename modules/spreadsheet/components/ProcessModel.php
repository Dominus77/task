<?php

namespace modules\spreadsheet\components;

use Yii;
use yii\helpers\FileHelper;
use modules\spreadsheet\traits\ModuleTrait;
use modules\spreadsheet\components\generator\ModelGenerator;

/**
 * Class ProcessModel
 * @package modules\spreadsheet\components
 */
class ProcessModel
{
    use ModuleTrait;

    /**
     * Create model
     *
     * @param string $tableName
     * @return bool
     */
    public function createModel($tableName)
    {
        $generator = new ModelGenerator();
        $generator->tableName = $tableName;
        $generator->modelClass = ucfirst($tableName);
        $generator->ns = $this->getNsModel();
        $generator->generate();
        return true;
    }

    /**
     * Remove model
     *
     * @param string $tableName
     * @return bool
     */
    public function removeModel($tableName)
    {
        $path = $this->getModelPath() . '/' . ucfirst($tableName) . '.php';
        FileHelper::unlink($path);
        if (!file_exists($path))
            return true;
        return false;
    }
}
