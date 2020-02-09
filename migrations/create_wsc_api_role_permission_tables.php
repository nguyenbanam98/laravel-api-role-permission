<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWscApiRolePermissionTables extends Migration
{
    public function up()
    {
        $tables = config('apiRolePermission.table_and_column_names');

        Schema::create($tables['roles_table'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create($tables['permissions_table'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create($tables['permission_role_table'], function (Blueprint $table) use ($tables) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on($tables['roles_table'])->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on($tables['permissions_table'])->onDelete('cascade');
        });

        Schema::create($tables['role_user_table'], function (Blueprint $table) use ($tables) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on($tables['roles_table'])->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on($tables['users_table'])->onDelete('cascade');
        });

        Schema::create($tables['permission_user_table'], function (Blueprint $table) use ($tables) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('permission_id')->references('id')->on($tables['permissions_table'])->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on($tables['users_table'])->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
}
