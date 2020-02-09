<?php

namespace WeSimplyCode\ApiRolePermission\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Permission
{
    public function roles(): BelongsToMany;

    public function users(): BelongsToMany;

    public static function create(array $permission = []): self;

    public static function getOrMake(string $name): self;

    public static function getByName(string $name): self;

    public static function getById(int $name): self;
}
