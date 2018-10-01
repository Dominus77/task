<?php

namespace modules\spreadsheet\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use modules\spreadsheet\traits\ModuleTrait;
use modules\spreadsheet\components\generator\ApiModelGenerator;
use modules\spreadsheet\components\generator\ApiControllerGenerator;
use modules\spreadsheet\components\generator\ApiConfigGenerator;

/**
 * Class ProcessApi
 * @package modules\spreadsheet\components
 */
class ProcessApi
{
    use ModuleTrait;

    /**
     * Create Api
     *
     * @param string $tableName
     * @return bool
     */
    public function createApi($tableName)
    {
        if ($this->processCreateModelApi($tableName)) {
            return $this->processCreateControllerApi($tableName);
        }
        return false;
    }

    /**
     * Create Model
     *
     * @param $tableName
     * @return bool
     */
    protected function processCreateModelApi($tableName)
    {
        $generator = new ApiModelGenerator();
        $generator->tableName = $tableName;
        $generator->modelClass = ucfirst($tableName);
        $generator->ns = $this->getNsApiModel();
        $generator->generate();
        return true;
    }

    /**
     * Create Controller
     *
     * @param $tableName
     * @return bool
     */
    protected function processCreateControllerApi($tableName)
    {
        $generator = new ApiControllerGenerator();
        $generator->modelClass = ucfirst($tableName);
        $generator->controllerClass = $this->getNsApiController() . '\\' . ucfirst($tableName) . 'Controller';
        $generator->generate();
        return $this->setApiConfig();
    }

    /**
     * Remove Api
     *
     * @param $tableName
     * @return bool
     */
    public function removeApi($tableName)
    {
        if ($this->removeModelApi($tableName)) {
            return $this->removeControllerApi($tableName);
        }
        return false;
    }

    /**
     * Remove model Api
     *
     * @param $tableName
     * @return bool
     */
    public function removeModelApi($tableName)
    {
        $path = $this->getApiModelPath() . '/' . ucfirst($tableName) . '.php';
        FileHelper::unlink($path);
        if (!file_exists($path))
            return true;
        return false;
    }

    /**
     * Remove controller Api
     *
     * @param $tableName
     * @return bool
     */
    public function removeControllerApi($tableName)
    {
        $path = $this->getApiControllerPath() . '/' . ucfirst($tableName) . 'Controller.php';
        FileHelper::unlink($path);
        if (!file_exists($path))
            return $this->setApiConfig();
        return false;
    }

    /**
     * Set config for api
     *
     * @return bool
     */
    protected function setApiConfig()
    {
        $items = $this->getNamesArray();
        $version = $this->getVersionApi();
        $generator = new ApiConfigGenerator();
        $generator->ns = 'modules/spreadsheet/config';
        if ($items) {
            foreach ($items as $item) {
                $generator->controllersItems[] = $version . '/' . $item;
            }
        }
        return $generator->generate();
    }

    /**
     * Return table names array
     *
     * @return array
     */
    protected function getNamesArray()
    {
        $files = $this->getFilesNames();
        $db = Yii::$app->db;
        $names = [];
        foreach ($files as $item) {
            if ($db->getTableSchema($item, true) !== null) {
                $names[] = $item;
            }
        }
        return $names;
    }
}
