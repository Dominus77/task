<?php
/**
 * @var $this yii\web\View
 * @var $model \modules\spreadsheet\components\Table
 */
?>
<div class="panel panel-default">
    <div class="panel-body">
        <h3>Еще не создано ни одной таблицы.</h3>
        <p>Что бы создать таблицу, воспользуйтесь консольным приложением. Наберите в консоли команду
        <pre><code>php yii spreadsheet/import/create-table</code></pre>
        и выберите из предложенного списка таблицу.</p>
        <p>Названия таблиц строятся на основе названий файлов <code>*.xls</code>, которые находятся в папке
        <pre><code>app/web/uploads/spreadsheet</code></pre>
        Если файл содержит только заголовки, но значения пустые, таблица не будет создана.
        </p>
        <p>
            Что бы посмотреть все доступные команды, в консоли наберите <code>php yii spreadsheet/import</code>
        </p>
    </div>
</div>
