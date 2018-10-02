# Yii2 Spreadsheet

Модуль Yii2 Spreadsheet. Импорт данных из файлов *.xls в базу данных

Файлы *.xls находятся в папке `app/web/uploads/spreadsheet`

### Консольные команды
```                  
spreadsheet/import/clear-data           Очистить таблицу
spreadsheet/import/create-table         Создать таблицу
spreadsheet/import/index (default)      Команды
spreadsheet/import/load-data            Загрузить данные в таблицу
spreadsheet/import/remove-table         Удалить таблицу
spreadsheet/import/show-files-names     Показать имена файлов
spreadsheet/import/show-tables-names    Показать имена созданых таблиц

```

### RESTful API
Доступ к API модуля по Bearer токену. Доступ к действиям по RBAC.
 
Ключ авторизации можно получить авторизовавшись на сайте, в разделе Профиль.

Авторизация: В заголовке запроса установить значение Authorization: Bearer <ваш_ключ>
```
Authorization: Bearer 51aGMh6_TJDKC9dpZPBaE23TX5NXruI3
```

#### Запросы
```
// Авторизованные по Bearer токену пользователи с разрешением RBAC на доступ
GET http://you_domain_name/api/v1/spreadsheet - Доступные таблицы, вывод с пагинацией
GET http://you_domain_name/api/v1/spreadsheet/test - Данные из таблицы test, вывод с пагинацией
GET http://you_domain_name/api/v1/spreadsheet/test?page=2 - Вторая страница данных из таблицы test

// Авторизованные по Bearer токену пользователи с разрешением RBAC на просмотр
GET http://you_domain_name/api/v1/spreadsheet/test/2 - Получение данных из таблицы test по id

// Авторизованные по Bearer токену пользователи, с разрешением RBAC на редактирование
PUT http://you_domain_name/api/v1/spreadsheet/test/3 - Редактирование данных с id 2 таблицы test
```
