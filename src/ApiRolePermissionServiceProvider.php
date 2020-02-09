<?php

namespace WeSimplyCode\ApiRolePermission;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class ApiRolePermissionServiceProvider extends ServiceProvider
{
    public function boot(Filesystem $filesystem)
    {
        $this->publishes([
            __DIR__ . '/../config/apiRolePermission.php' => config_path('apiRolePermission.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../migrations/create_wsc_api_role_permission_tables.php' => $this->getMigrationFileName($filesystem),
        ], 'migrations');
    }

    public function register()
    { }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param Filesystem $filesystem
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem): string
    {
        $timestamp = date('Y_m_d_His');
        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path . '*_create_wsc_api_role_permission_tables.php');
            })->push($this->app->databasePath() . "/migrations/{$timestamp}_create_wsc_api_role_permission_tables.php")
            ->first();
    }
}
