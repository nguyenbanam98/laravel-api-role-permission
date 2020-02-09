<?php

namespace WeSimplyCode\ApiRolePermission\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use WeSimplyCode\ApiRolePermission\Models\Permission;

trait UsePermissions
{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            'WeSimplyCode\ApiRolePermission\Models\Permission',
            config('rolePermission.table_and_column_names.permission_user_table'),
            config('rolePermission.table_and_column_names.user_id_column'),
            'permission_id');
    }

    public function givePermissions(array $permissions = [])
    {
        foreach ($permissions as $permission){
            $p = Permission::whereName($permission)->first();

            if (!$p){
                throw new \Exception('HasAllPermissions '.$permission.' does not  exist!');
            }

            $this->permissions()->sync($p, false);
        }
    }

    public function hasPermission(string $permission_name):bool
    {
        $permissions = $this->permissions;

        foreach ($permissions as $permission)
        {
            if ($permission->name == $permission_name)
            {
                return true;
            }
        }
        return false;
    }

    public function hasAnyPermission(array $permissions = []):bool
    {
        foreach ($permissions as $permission)
        {
            if (in_array($permission, $this->getPermissions())){
                return true;
            }
        }
        return false;
    }

    public function hasAllPermissions(array $permissions = []):bool {
        foreach ($permissions as $permission)
        {
            if (!in_array($permission, $this->getPermissions())){
                return false;
            }
        }
        return true;
    }

    public function getPermissions()
    {
        $p = $this->permissions->toArray();
        $permissions = array();

        foreach ($p as $item)
        {
            array_push($permissions, $item['name']);
        }
        return $permissions;
    }
}
