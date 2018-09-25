<?php

namespace app\components;

/**
 * Class Rbac
 * @package app\components
 */
class Rbac
{
    // Роли
    const ROLE_ADMIN = 'admin';
    const ROLE_DESCRIPTION_ADMIN = 'Admin';

    const ROLE_USER = 'user';
    const ROLE_DESCRIPTION_USER = 'User';

    // Разрешения
    const PERMISSION_ACCESS_TABLE = 'accessTable';
    const PERMISSION_DESCRIPTION_ACCESS_TABLE = 'Access Table';

    const PERMISSION_VIEW_TABLE = 'viewTable';
    const PERMISSION_DESCRIPTION_VIEW_TABLE = 'View Table';

    const PERMISSION_EDIT_TABLE = 'editTable';
    const PERMISSION_DESCRIPTION_EDIT_TABLE = 'Edit Table';
}