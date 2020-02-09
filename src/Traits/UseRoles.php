<?php

namespace WeSimplyCode\ApiRolePermission\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use WeSimplyCode\ApiRolePermission\Models\Role;

trait UseRoles
{
    use UsePermissions;

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            'WeSimplyCode\ApiRolePermission\Models\Role',
            config('rolePermission.table_and_column_names.role_user_table'),
            config('rolePermission.table_and_column_names.user_id_column'),
            'role_id'
        );
    }

    public function giveRoles(array $roles = [])
    {
        foreach ($roles as $role) {
            $r = Role::whereName($role)->first();

            if (!$r) {
                throw new \Exception('Role ' . $role . ' does not  exist!');
            }

            $this->roles()->sync($r, false);
        }
    }

    public function hasRole(string $role_name): bool
    {
        $roles = $this->roles;

        foreach ($roles as $role) {
            if ($role->name == $role_name) {
                return true;
            }
        }
        return false;
    }

    public function hasAnyRole(array $roles = []): bool
    {
        foreach ($roles as $role) {
            if (in_array($role, $this->getRoles())) {
                return true;
            }
        }
        return false;
    }

    public function hasAllRoles(array $roles = []): bool
    {
        foreach ($roles as $role) {
            if (!in_array($role, $this->getRoles())) {
                return false;
            }
        }
        return true;
    }

    public function getRoles()
    {
        $r = $this->roles->toArray();
        $roles = array();

        foreach ($r as $item) {
            array_push($roles, $item['name']);
        }
        return $roles;
    }

    public function hasAllPermissionsByRoles(array $permissions): bool
    {
        $p = array();

        foreach ($this->roles as $role) {
            foreach ($role->permissions as $item) {
                array_push($p, $item->name);
            }
        }

        foreach ($permissions as $permission) {
            if (!in_array($permission, $p)) {
                return false;
            }
        }

        return true;
    }

    public function hasAnyPermissionByRoles(array $permissions): bool
    {
        foreach ($this->roles as $role) {
            foreach ($role->permissions as $item) {
                foreach ($permissions as $permission) {
                    if ($permission == $item->name) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
