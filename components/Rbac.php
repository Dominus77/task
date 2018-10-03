<?php

namespace app\components;

/**
 * Class Rbac
 * @package app\components
 */
class Rbac
{
    /**
     * Роли
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_DESCRIPTION_ADMIN = 'Admin';

    const ROLE_USER = 'user';
    const ROLE_DESCRIPTION_USER = 'User';

    /**
     * Разрешения
     */

    // User
    const PERMISSION_VIEW_USER = 'viewUser';
    const PERMISSION_DESCRIPTION_VIEW_USER = 'View User';

    const PERMISSION_UPDATE_USER = 'updateUser';
    const PERMISSION_DESCRIPTION_UPDATE_USER = 'Update User';

    const PERMISSION_DELETE_USER = 'deleteUser';
    const PERMISSION_DESCRIPTION_DELETE_USER = 'Delete User';

    // Spreadsheet
    const PERMISSION_ACCESS_TABLE = 'accessTable';
    const PERMISSION_DESCRIPTION_ACCESS_TABLE = 'Access Table';

    const PERMISSION_VIEW_TABLE = 'viewTable';
    const PERMISSION_DESCRIPTION_VIEW_TABLE = 'View Table';

    const PERMISSION_EDIT_TABLE = 'editTable';
    const PERMISSION_DESCRIPTION_EDIT_TABLE = 'Edit Table';
}