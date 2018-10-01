<?php

namespace modules\spreadsheet\components\generator;

use Yii;
use yii\gii\generators\controller\Generator;

/**
 * Class ApiRulesGenerator
 * @package modules\spreadsheet\components\generator
 */
class ApiRulesGenerator extends Generator
{
    public $ns;
    public $controllersItems;

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
            Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/apiRules.php',
            $this->render('config.php', ['controllers' => $this->controllersItems])
        );
        $codeFile->operation = GeneratorCodeFile::OP_OVERWRITE;
        return $codeFile->save();
    }
}
