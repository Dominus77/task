<?php
/**
 * This is the template for generating a config for api controller.
 */

echo "<?php\n";
?>

<?php if(!empty($controllers)) :?>
return [
<?php
foreach ($controllers as $name): ?>
    <?= "'$name'" . ",\n" ?>
<?php endforeach; ?>
];
<?php else : ?>
return [];
<?php endif; ?>
