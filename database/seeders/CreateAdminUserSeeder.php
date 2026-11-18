<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = config('app.seed_admin_password');
        if (! is_string($password) || strlen($password) < 12) {
            throw new RuntimeException('Set SEED_ADMIN_PASSWORD to at least 12 characters before seeding.');
        }

        $user = User::create([
            'name' => 'administrator',
            'email' => 'admin@email.com',
            'password' => bcrypt($password),
        ]);

        $role = Role::create(['name' => 'Administrator']);

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);

        // Creates the user profile
        Employee::create([
            'user_id' => $user->id,
            'name' => '',
            'latin_name' => '',
            'phone' => '',
            'position_id' => $role->id,
        ]);

    }
}
