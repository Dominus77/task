# Task

## Установка
Клонируем проект
```
git clone https://github.com/Dominus77/task.git task

```
Переходим в папку с проектом и выполняем
```
composer self-update
composer install
composer update
```

## Настройка
Настраиваем базу данных в [[app/config/web/db.php]]

```
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=task_involta',
    //...
];
```
Выполняем миграцию
```
php yii migrate
```
## Использование
### Консольные команды
Все доступные консольные команды, можно посмотреть набрав в консоли:
```
php yii
```
### RBAC
RBAC использует [[yii\rbac\PhpManager]]. Роли и разрешения находятся в [[app/components/Rbac.php]].

Инициализируем RBAC
```
php yii rbac/init

```

Создаем пользователя. Вводим команду и следуем инструкции.
```
php yii user/create
```
Меняем статус у пользователя на Активный. Вводим команду и следуем инструкции.
```
php yii user/activate
```
Удаляем пользователя. Вводим команду, следуем инструкции.
```
php yii user/remove

```
Присваиваем пользователю роль. Вводим команду и следуем инструкции.
```
php yii role/assign
```
Отвязываем роль. Вводим команду и следуем инструкции.
```
php yii role/revoke
```
### Ссылки
```
http://you_domain_name/spreadsheet
```
Файлы *.xls находятся в папке `app/web/uploads/spreadsheet`

[Подробнее о модуле Spreadsheet](https://github.com/Dominus77/task/blob/master/modules/spreadsheet/README.md)

RESTful API

```
GET http://you_domain_name/api/v1/message
GET http://you_domain_name/api/v1/users
GET http://you_domain_name/api/v1/users/1
GET http://you_domain_name/api/v1/users/1?expand=created_at
POST http://you_domain_name/api/v1/users/signup
GET http://you_domain_name/api/v1/users/profile
GET http://you_domain_name/api/v1/users/profile?expand=aut_key
```

## Тесты
Для тестов, настраиваем базу данных в [[app/config/test_db.php]]
```
$db['dsn'] = 'mysql:host=localhost;dbname=task_involta_tests';
```
Выполняем миграцию

Windows:
```
php tests\bin\yii migrate
```
Для других систем:
```
php tests/bin/yii migrate
```
### Запуск тестов

Для Windows:
```
vendor\bin\codecept build
vendor\bin\codecept run
```
Для других систем:
```
vendor/bin/codecept build
vendor/bin/codecept run
```
