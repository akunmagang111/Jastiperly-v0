<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * Users
         */
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['finance', 'admin', 'superadmin'])->default('finance');
            $table->enum('account_status', ['active', 'inactive'])->default('active');
            $table->rememberToken();
            $table->timestamps();
        });

        /**
         * User Details
         */
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('verified_type')->default(false);
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('date_birth')->nullable();
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_number')->nullable();
            $table->string('id_card_image')->nullable();
            $table->string('pasport_image')->nullable();
            $table->string('account_image')->nullable();
            $table->timestamps();
        });

        /**
         * Product Categories
         */
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        /**
         * Products
         */
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submiter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->enum('approval', ['pending', 'approved', 'declined'])->default('pending');
            $table->timestamps();
        });

        /**
         * Payment Methods
         */
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['wallet', 'bank']);
            $table->string('name');
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->timestamps();
        });

        /**
         * Buy Transactions
         */
        Schema::create('buy_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('traveler_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('total_price', 15, 2);
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('cascade');
            $table->string('payment_proof')->nullable();
            $table->enum('payment_status', ['pending', 'approved', 'declined'])->default('pending');
            $table->timestamps();
        });

        /**
         * Send Transactions
         */
        Schema::create('send_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reciever_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('dimension')->nullable();
            $table->string('weight')->nullable();
            $table->string('delivery_code')->nullable();
            $table->enum('delivery_method', ['Reguler', 'Express', 'Kargo']);
            $table->enum('delivery_type', ['Dalam Negeri', 'Luar Negeri']);
            $table->string('delivery_image')->nullable();
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('cascade');
            $table->string('payment_proof')->nullable();
            $table->enum('payment_status', ['pending', 'approved', 'declined'])->default('pending');
            $table->timestamps();
        });

        /**
         * Refunds
         */
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buy_transaction_id')->constrained('buy_transactions')->onDelete('cascade');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending');
            $table->timestamps();
        });

        /**
         * Sessions
         */
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        /**
         * Cache (untuk CACHE_DRIVER=database)
         */
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value');
            $table->integer('expiration')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('send_transactions');
        Schema::dropIfExists('buy_transactions');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('user_details');
        Schema::dropIfExists('users');
    }
};