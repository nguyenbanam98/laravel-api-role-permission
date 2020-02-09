<?php

namespace WeSimplyCode\ApiRolePermission\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Role
{
    public function permissions(): BelongsToMany;

    public function users(): BelongsToMany;

    public static function create(array $role = []): self;

    public static function getOrMake(string $name): self;

    public static function getByName(string $name): self;

    public static function getById(int $name): self;

    public function givePermissions(array $permissions = []);
}
