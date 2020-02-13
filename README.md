# Role-Permission

Simple role-permission package for api's build with laravel framework.
This package depends on <a href="https://laravel.com/docs/5.8/passport">Laravel Passport</a>.
Simply assign a role to user, assign permissions to roles or assign permissions directly to a user.

### Installing

```
composer require wesimplycode/laravel-api-role-permission
```

```
php artisan vendor:publish --provider="WeSimplyCode\ApiRolePermission\ApiRolePermissionServiceProvider" --tag="config"
```

```
php artisan vendor:publish --provider="WeSimplyCode\ApiRolePermission\ApiRolePermissionServiceProvider" --tag="migrations"
```

When using Laravel 5.5 or higher the service provider will automatically get registered.

After publishing the migrations you can create all the tables needed by running

```
php artisan migrate
```

### Usage

**To start using the roles and permissions simply add `use UseRoles` to the `User` model.**

#### Creating roles & permissions and assigning them

```php
use WeSimplyCode\ApiRolePermission\Models\Permission;
use WeSimplyCode\ApiRolePermission\Models\Role;
use App\User;

// Create role
$role = Role::create(['name'=>'user']);

// Create permission
$permission = Permission::create(['name'=>'browse authorized']);

// Assign permission to role (givePermissions accepts an array, which means you can assign multiple permissions at once)
$role->givePermissions(['browse authorized']);


$user = User::find(1);

// Assign role to a user (giveRoles accepts an array, which means you can assign multiple roles at once)
$user->giveRoles(['user']);

// Assign permission to a user (givePermissions accepts an array, which means you can assign multiple roles at once)
$user->givePermissions(['browse authorized']);
```

#### Checking if a user has a certain role/permission

```php
use App\User;

$user = User::find(1);

// hasRole accepts a string and returns true if the role is assigned to the user
$user->hasRole($role);

// hasAnyRole accepts an array with roles and returns true if at least one of the roles in the array is assigned to the user
$user->hasAnyRole($roles);

// hasAllRoles accepts an array with roles and returns true if all the roles in the array are assigned to the user
$user->hasAllRoles($roles);

// hasPermissionsByRoles accepts an array with permissions and returns true if the permissions are attached to roles assigned to the user
$user->hasPermissionsByRoles($permissions);


// hasPermission accepts a string and returns true if the permission is assigned to the user
$user->hasPermission($permission);

// hasAnyPermission accepts an array with permissions and returns true if at least one of the permissions in the array is assigned to the user
$user->hasAnyPermission($permissions);

// hasAllPermissions accepts an array with permissions and returns true if all the permissions in the array are assigned to the user
$user->hasAllPermissions($permissions);
```

#### Middleware

This package comes with five middlewares: **_hasAllPermissions, hasAllRoles, hasAnyRole, hasAllPermissionsByRoles, hasAnyPermissionByRoles_**. You can add as many as needed.
Add the standard middlewares to your `app/Http/Kernel.php`

```php
protected $routeMiddleware = [
        'hasAllPermissions' => \WeSimplyCode\ApiRolePermission\Middleware\HasAllPermissions::class,
        'hasAllRoles' => \WeSimplyCode\ApiRolePermission\Middleware\HasAllRoles::class,
        'hasAnyRole' => \WeSimplyCode\ApiRolePermission\Middleware\HasAnyRole::class,
        'hasAllPermissionsByRoles' => \WeSimplyCode\ApiRolePermission\Middleware\HasAllPermissionsByRoles::class,
        'hasAnyPermissionByRoles' => \WeSimplyCode\ApiRolePermission\Middleware\HasAnyPermissionByRoles::class,
    ];
```

Protect routes with middleware examples:

```php
// Protect routes with the hasAllRoles middleware
Route::middleware(['hasAllRoles:admin'])
	->group(function (){
		Route::any('/test', 'TestController@test')->name('test');
	});

// Enter more than one role seperated by |
Route::middleware(['hasAllRoles:moderator|admin'])
	->group(function (){
		Route::any('/test', 'TestController@test')->name('test');
    });

// Protect routes with the hasAnyRole middleware. If the user has any of the roles access will be granted
Route::middleware(['hasAnyRole:admin'])
	->group(function (){
		Route::any('/test', 'TestController@test')->name('test');
	});

// Enter more than one role seperated by |. If the user has any of the roles access will be granted
Route::middleware(['hasAnyRole:moderator|admin'])
	->group(function (){
		Route::any('/test', 'TestController@test')->name('test');
	});

// Protect routes with the hasAllPermissions middleware
Route::middleware(['hasAllPermissions:browse authorized'])
	->group(function (){
		Route::any('/test', 'TestController@test')->name('test');
	});

// Enter more than one permission seperated by |
Route::middleware(['hasAllPermissions:browse authorized|manage users'])
	->group(function (){
		Route::any('/test', 'TestController@test')->name('test');
	});

// Protect routes with the hasAllPermissionsByRoles middleware. User must have all permissions through their roles to gain access
Route::middleware(['hasAllPermissionsByRoles:browse authorized'])
	->group(function (){
		Route::any('/test', 'TestController@test')->name('test');
	});

// Enter more than one permission seperated by |. User must have all permissions through their roles to gain access
Route::middleware(['hasAllPermissionsByRoles:browse authorized|manage users'])
	->group(function (){
		Route::any('/test', 'TestController@test')->name('test');
    });

// Protect routes with the hasAnyPermissionByRoles middleware. User must have at least one of the permissions through their roles to gain access
Route::middleware(['hasAnyPermissionByRoles:browse authorized'])
	->group(function (){
		Route::any('/test', 'TestController@test')->name('test');
	});

// Enter more than one permission seperated by |. User must have at least one of the permissions through their roles to gain access
Route::middleware(['hasAnyPermissionByRoles:browse authorized|manage users'])
	->group(function (){
		Route::any('/test', 'TestController@test')->name('test');
	});
```

```php
// All the middlewares can also be used directly in controllers
public function __construct()
{
    $this->middleware('hasAllRoles:user|moderator');
}

public function __construct()
{
    $this->middleware('hasAnyRole:user|moderator');
}

public function __construct()
{
    $this->middleware('hasAllPermissions:browse authorized|manage users');
}

public function __construct()
{
    $this->middleware('hasAllPermissionsByRoles:browse authorized|manage users');
}

public function __construct()
{
    $this->middleware('hasAnyPermissionByRoles:browse authorized|manage users');
}
```

```php
// After using one of the middlewares you can retrieve a user simply by using the auth() helper
auth('api')->user()
```

#### Database seeder

```php
use Illuminate\Database\Seeder;
use WeSimplyCode\ApiRolePermission\Models\Permission;
use WeSimplyCode\ApiRolePermission\Models\Role;
class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name'=>'browse authorized']);
        Permission::create(['name'=>'manage users']);

        Role::create(['name'=>'user'])->givePermissions(['browse authorized']);
        Role::create(['name'=>'moderator'])
            ->givePermissions([
                'browse authorized',
                'manage users'
            ]);

    }
}
```

## Author

- **WeSimplycode - Sunil Kisoensingh**

## License

This project is licensed under the MIT License
