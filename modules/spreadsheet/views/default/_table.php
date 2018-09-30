<?php
/**
 * @var $this yii\web\View
 * @var $model \modules\spreadsheet\components\Table
 */
?>
<div class="panel panel-default">
    <div class="panel-body">
        <p>Что бы загрузить данные в таблицу, воспользуйтесь консольным приложением. Наберите в консоли команду <code>php
                yii spreadsheet/import/load-data</code>
            и выберите из предложенного списка созданную таблицу.
        </p>
        <p>
            Работать с данными таблицы могут пользователи имеющие соответствующие разрешения установленные в RBAC.
        </p>
        <p>
            Управление правами происходит в консольном приложении.
            Присвоение роли пользователю:
        <pre><code>php yii role/assign</code></pre>
        Отвязка роли от пользователя:
        <pre><code>php yii role/revoke</code></pre>
        </p>
        <p>
            Что бы посмотреть все доступные команды, в консоли наберите <code>php yii</code>, команды для модуля <code>php
                yii spreadsheet/import</code>
        </p>
    </div>
</div>
