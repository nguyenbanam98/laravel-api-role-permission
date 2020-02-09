<?php

return [

    /*
     * Names of the database tables
     */
    'table_and_column_names' => [
        //The column name of the user_id field in the pivot table (by default'user_id')
        'user_id_column' => 'user_id',

        //Roles table name
        'roles_table' => 'roles',

        //Permissions table name
        'permissions_table' => 'permissions',

        //Permission_Role table name (used for belongsToMany relation)
        'permission_role_table' => 'permission_role',

        //The users table name
        'users_table' => 'users',

        //Role_User table name (used for belongsToMany relation)
        'role_user_table'  => 'role_user',

        //Permission_User table name (used for belongsToMany relation)
        'permission_user_table'  => 'permission_user',
    ]

];
