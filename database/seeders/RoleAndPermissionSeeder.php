<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إزالة الكاش لتجنب مشاكل التكرار
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $bookPermissions = ['book.store', 'book.edit', 'book.delete'];
        foreach ($bookPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $userPermissions = ['user.view', 'user.store', 'user.edit', 'user.delete'];
        foreach ($userPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'author']);
        $editor = Role::firstOrCreate(['name' => 'editor']);

        $admin->givePermissionTo(Permission::all());
        $editor->givePermissionTo([ 'book.delete', 'book.store', 'book.edit']);

        // create some user
        $admin = User::firstOrCreate([
            'email' => "abdoarfat2006@gmail.com",
        ],[
            'name_ar' => 'عبد الرحمن',
            "name_en" => "Abdelrhman",
            "email" => "abdoarfat2006@gmail.com",
            "password" => bcrypt('password'),
        ]);

        $admin->assignRole('admin');
        $admin = User::firstOrCreate([
            'email' => "editor@gmail.com",
        ],[
            'name_ar' => 'عبد الرحمن',
            "name_en" => "Abdelrhman",
            "email" => "editor@gmail.com",
            "password" => bcrypt('password'),
        ]);

        $admin->assignRole('editor');

        $author= user::firstOrCreate([
           "email"  => "ahmedShawki@gmail.com",
        ],[
           "name_ar" => "احمد شوقي" ,
           "name_en" => "Ahmed Shawki",
           "email"  => "ahmedShawki@gmail.com",
           "password" => bcrypt('password'),
        ]);
        $author->assignRole('author');

        $this->command->info('Roles and permissions seeded successfully: Admin & Author & Editor!');
    }
}
