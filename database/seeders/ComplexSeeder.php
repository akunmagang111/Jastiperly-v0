<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

use App\Models\User;
use App\Models\UserDetail;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\BuyTransaction;
use App\Models\SendTransaction;
use App\Models\Refund;

class ComplexSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        /**
         * Users dengan role
         */
        $users = [
            ['name' => 'Super Admin', 'email' => 'superadmin@example.com', 'role' => 'superadmin'],
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'role' => 'admin'],
            ['name' => 'Finance User', 'email' => 'finance@example.com', 'role' => 'finance'],
            ['name' => 'Traveler User', 'email' => 'traveler@example.com', 'role' => 'traveler'], // sementara assign finance
            ['name' => 'Customer User', 'email' => 'customer@example.com', 'role' => 'customer'], // sementara assign finance
        ];

        $userModels = [];
        foreach ($users as $u) {
            $user = User::create([
                'name' => $u['name'],
                'email' => $u['email'],
                'password' => Hash::make('password'),
                'role' => $u['role'],
                'account_status' => 'active',
            ]);
            $userModels[] = $user;

            // Buat UserDetail untuk tiap user
            UserDetail::create([
                'user_id' => $user->id,
                'verified_type' => $faker->boolean(),
                'name' => $u['name'] . ' Detail',
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'date_birth' => $faker->date(),
                'gender' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'bank_name' => $faker->randomElement(['BCA', 'Mandiri', 'BNI']),
                'bank_number' => $faker->bankAccountNumber(),
            ]);
        }

        $traveler = $userModels[3]; // Traveler User
        $customer = $userModels[4]; // Customer User

        /**
         * Product Categories
         */
        $categories = ['Elektronik', 'Fashion', 'Makanan', 'Kosmetik', 'Perabotan'];
        foreach ($categories as $cat) {
            ProductCategory::create([
                'name' => $cat,
                'description' => "Kategori {$cat}",
            ]);
        }

        /**
         * Payment Methods
         */
        $methods = [
            ['type' => 'bank', 'name' => 'BCA', 'account_name' => 'Super Admin', 'account_number' => '111222333'],
            ['type' => 'wallet', 'name' => 'OVO', 'account_name' => 'Admin User', 'account_number' => '08123456789'],
        ];
        foreach ($methods as $m) {
            PaymentMethod::create($m);
        }

        /**
         * 100 Produk Dummy
         */
        $categoryIds = ProductCategory::pluck('id')->toArray();

        $productModels = [];
        for ($i = 0; $i < 100; $i++) {
            $product = Product::create([
                'submiter_id' => $traveler->id,
                'category_id' => $faker->randomElement($categoryIds),
                'name' => $faker->words(3, true),
                'description' => $faker->sentence(10),
                'price' => $faker->numberBetween(10000, 5000000),
                'image' => $faker->imageUrl(640, 480, 'products', true, 'Jastiperly'),
                'status' => $faker->randomElement(['active', 'inactive']),
                'approval' => $faker->randomElement(['pending', 'approved', 'declined']),
            ]);
            $productModels[] = $product;
        }

        /**
         * Buat 10 Transaksi Beli (distribusi ke bulan ini & bulan lalu)
         */
        $paymentMethodIds = PaymentMethod::pluck('id')->toArray();

        $totalDays = 60; // 30 hari lalu + 30 hari ini
        $today = now();
        $startDate = $today->copy()->subDays($totalDays - 1); // mulai dari 59 hari lalu

        for ($i = 0; $i < 100; $i++) {
            $product = $faker->randomElement($productModels);
            $quantity = $faker->numberBetween(1, 5);
            $total = $quantity * $product->price;

            // Tentukan tanggal transaksi (dibagi rata)
            $dayOffset = intdiv($i, 100 / $totalDays); 
            $transDate = $startDate->copy()->addDays($dayOffset);

            $buy = BuyTransaction::create([
                'buyer_id' => $customer->id,
                'traveler_id' => $traveler->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'total_price' => $total,
                'payment_method_id' => $faker->randomElement($paymentMethodIds),
                'payment_status' => $faker->randomElement(['pending', 'approved']),
                'created_at' => $transDate,
                'updated_at' => $transDate,
            ]);

            // 30% transaksi punya refund
            if ($faker->boolean(30)) {
                Refund::create([
                    'buy_transaction_id' => $buy->id,
                    'reason' => $faker->sentence(),
                    'status' => $faker->randomElement(['pending', 'approved', 'declined']),
                    'created_at' => $transDate,
                    'updated_at' => $transDate,
                ]);
            }
        }

        /**
         * Buat 100 Transaksi Kirim (juga distribusi ke 60 hari)
         */
        for ($i = 0; $i < 100; $i++) {
            $product = $faker->randomElement($productModels);

            $dayOffset = intdiv($i, 100 / $totalDays); 
            $transDate = $startDate->copy()->addDays($dayOffset);

            SendTransaction::create([
                'sender_id' => $traveler->id,
                'reciever_id' => $customer->id,
                'product_id' => $product->id,
                'dimension' => $faker->numberBetween(10, 100) . ' cm',
                'weight' => $faker->numberBetween(1, 10) . ' kg',
                'delivery_code' => strtoupper($faker->bothify('DEL###??')),
                'delivery_method' => $faker->randomElement(['Reguler', 'Express', 'Kargo']),
                'delivery_type' => $faker->randomElement(['Dalam Negeri', 'Luar Negeri']),
                'payment_method_id' => $faker->randomElement($paymentMethodIds),
                'payment_status' => $faker->randomElement(['pending', 'approved']),
                'created_at' => $transDate,
                'updated_at' => $transDate,
            ]);
        }
    }
}