<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    protected static ?string $password;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(5)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Category::factory(50)->create();
        Store::factory(50)->create();
        Product::factory(150)->create();
        Admin::factory(5)->create();

        // in here i need to add my seeders class
        $this->call(UserSeeder::class);

        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password'=> static::$password ??= Hash::make('password'),
            'username' => 'superadmin',
            'phone_number' => '1234567890',
            'super_admin' => true,
        ]);
    }
}
