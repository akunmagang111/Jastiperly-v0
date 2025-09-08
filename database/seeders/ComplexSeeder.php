<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

use App\Models\User;
use App\Models\UserDetail;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\PaymentMethod;

class ComplexSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        /**
         * Users dengan role
         */
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'account_status' => 'active',
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'account_status' => 'active',
        ]);

        $finance = User::create([
            'name' => 'Finance User',
            'email' => 'finance@example.com',
            'password' => Hash::make('password'),
            'role' => 'finance',
            'account_status' => 'active',
        ]);

        // Buat user traveler & customer juga
        $traveler = User::create([
            'name' => 'Traveler User',
            'email' => 'traveler@example.com',
            'password' => Hash::make('password'),
            'role' => 'finance', // sistem pakai role, jadi kita assign salah satu
            'account_status' => 'active',
        ]);

        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'finance',
            'account_status' => 'active',
        ]);

        /**
         * User details sample
         */
        UserDetail::create([
            'user_id' => $traveler->id,
            'verified_type' => true,
            'name' => 'Traveler Lengkap',
            'phone' => '08123456789',
            'address' => 'Jakarta',
            'date_birth' => '1990-05-15',
            'gender' => 'Laki-laki',
            'bank_name' => 'BCA',
            'bank_number' => '1234567890',
        ]);

        /**
         * Product Categories
         */
        $categories = [
            'Elektronik',
            'Fashion',
            'Makanan',
            'Kosmetik',
            'Perabotan',
        ];

        foreach ($categories as $cat) {
            ProductCategory::create([
                'name' => $cat,
                'description' => "Kategori {$cat}",
            ]);
        }

        /**
         * Payment Methods
         */
        PaymentMethod::create([
            'type' => 'bank',
            'name' => 'BCA',
            'account_name' => 'Super Admin',
            'account_number' => '111222333',
        ]);

        PaymentMethod::create([
            'type' => 'wallet',
            'name' => 'OVO',
            'account_name' => 'Admin User',
            'account_number' => '08123456789',
        ]);

        /**
         * 100 Produk Dummy
         */
        $categoryIds = ProductCategory::pluck('id')->toArray();

        for ($i = 0; $i < 100; $i++) {
            Product::create([
                'submiter_id' => $traveler->id,
                'category_id' => $faker->randomElement($categoryIds),
                'name' => $faker->words(3, true), // nama produk random
                'description' => $faker->sentence(10),
                'price' => $faker->numberBetween(10000, 5000000),
                'image' => $faker->imageUrl(640, 480, 'products', true, 'Jastiperly'),
                'status' => $faker->randomElement(['active', 'inactive']),
                'approval' => $faker->randomElement(['pending', 'approved', 'declined']),
            ]);
        }
    }
}