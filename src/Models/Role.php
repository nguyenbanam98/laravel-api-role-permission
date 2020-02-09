<?php

namespace WeSimplyCode\ApiRolePermission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use WeSimplyCode\ApiRolePermission\Contracts\Role as RoleContract;

class Role extends Model implements RoleContract
{
    protected $guarded = ['id'];

    protected $hidden = ['id', 'created_at', 'updated_at', 'pivot'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            'WeSimplyCode\ApiRolePermission\Models\Permission',
            config('apiRolePermission.table_and_column_names.permission_role_table'),
            'role_id',
            'permission_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            config('apiRolePermission.user_model_name'),
            config('apiRolePermission.table_and_column_names.role_user_table'),
            'role_id',
            config('apiRolePermission.table_and_column_names.user_id_column'));
    }

    public static function create(array $role = []): RoleContract
    {
        if (static::whereName($role['name'])->first())
        {
            throw new \Exception('Role '.$role['name'].' already  exists!');
        }

        return static::query()->create($role);
    }

    public static function getOrMake(string $name): RoleContract
    {
        $role = static::whereName($name)->first();

        if (!$role)
        {
            return static::query()->create(['name'=>$name]);
        }

        return $role;
    }

    public static function getByName(string $name): RoleContract
    {
        $role = static::whereName($name)->first();

        if (!$role){
            throw new \Exception('Role '.$name.' does not  exist!');
        }

        return $role;
    }

    public static function getById(int $id): RoleContract
    {
        $role = static::whereId($id)->first();

        if (!$role){
            throw new \Exception('Role does not  exist!');
        }

        return $role;
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
}
