<?php

namespace modules\spreadsheet\components\generator;

use Yii;
use yii\gii\generators\controller\Generator;

/**
 * Class ControllerGenerator
 * @package modules\spreadsheet\components\generator
 */
class ApiControllerGenerator extends Generator
{
    public $modelClass;

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $codeFile = new GeneratorCodeFile(
            $this->getControllerFile(),
            $this->render('api_controller.php')
        );
        $codeFile->operation = GeneratorCodeFile::OP_OVERWRITE;
        return $codeFile->save();
    }
}
