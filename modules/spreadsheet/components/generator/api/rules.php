<?php
/**
 * This is the template for generating a config for api controller.
 */

echo "<?php\n";
?>

<?php if(!empty($controllers)) :?>
return [
    'class' => 'yii\rest\UrlRule',
    'controller' => [
<?php
foreach ($controllers as $key => $name): ?>
        <?= "'$key' => '$name',\n" ?>
<?php endforeach; ?>
    ],
    'pluralize' => false,
];
<?php endif; ?>
