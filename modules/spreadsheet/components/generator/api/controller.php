<?php
/**
 * This is the template for generating a controller class file.
 */

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator modules\spreadsheet\components\generator\ApiControllerGenerator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);

echo "<?php\n";
?>

namespace <?= $generator->getControllerNamespace() ?>;

/**
 * <?= $controllerClass ?> implements the API actions for <?= $modelClass ?> model.
 */
class <?= StringHelper::basename($generator->controllerClass) ?> extends ApiController<?= "\n" ?>
{
    /**
     * @var string
     */
    public $modelClass = 'api\modules\v1\models\<?= $modelClass ?>';

}
