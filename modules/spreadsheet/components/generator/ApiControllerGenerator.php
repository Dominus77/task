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
    public $template = 'api';
    public $templates = [
        'api' => '@modules/spreadsheet/components/generator/api'
    ];

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $codeFile = new GeneratorCodeFile(
            $this->getControllerFile(),
            $this->render('controller.php')
        );
        $codeFile->operation = GeneratorCodeFile::OP_OVERWRITE;
        return $codeFile->save();
    }
}
