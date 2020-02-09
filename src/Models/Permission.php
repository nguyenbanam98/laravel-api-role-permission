<?php

namespace WeSimplyCode\ApiRolePermission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use WeSimplyCode\ApiRolePermission\Contracts\Permission as PermissionContract;

class Permission extends Model implements PermissionContract
{
    protected $guarded = ['id'];

    protected $hidden = ['id', 'created_at', 'updated_at', 'pivot'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            'WeSimplyCode\ApiRolePermission\Models\Role',
            config('apiRolePermission.table_and_column_names.permission_role_table'),
            'permission_id',
            'role_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            config('apiRolePermission.user_model_name'),
            config('apiRolePermission.table_and_column_names.permission_user_table'),
            'permission_id',
            config('apiRolePermission.table_and_column_names.user_id_column'));
    }

    public static function create(array $permission = []): PermissionContract
    {
        if (static::whereName($permission['name'])->first())
        {
            throw new \Exception('HasAllPermissions '.$permission['name'].' already  exists!');
        }

        return static::query()->create($permission);
    }

    public static function getOrMake(string $name): PermissionContract
    {
        $permission = static::whereName($name)->first();

        if (!$permission)
        {
            return static::query()->create(['name'=>$name]);
        }

        return $permission;
    }

    public static function getByName(string $name): PermissionContract
    {
        $permission = static::whereName($name)->first();

        if (!$permission){
            throw new \Exception('HasAllPermissions '.$name.' does not  exist!');
        }

        return $permission;
    }

    public static function getById(int $id): PermissionContract
    {
        $permission = static::whereId($id)->first();

        if (!$permission){
            throw new \Exception('HasAllPermissions does not  exist!');
        }

        return $permission;
    }
}
