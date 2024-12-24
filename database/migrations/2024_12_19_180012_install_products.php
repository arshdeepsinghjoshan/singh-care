<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128)->nullable();
            $table->string('slug', 128)->nullable();
            $table->string('product_code', 128)->nullable();
            $table->string('hsn_code', 128)->nullable();
            $table->string('batch_no', 128)->nullable();
            $table->string('agency_name', 255)->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 20, 6)->unsigned()->default(0);
            $table->decimal('distribution_price', 20, 6)->unsigned()->default(0);
            $table->integer('state_id')->default(1);
            $table->integer('type_id')->nullable(); // 1 = Package
            $table->string('image')->nullable();
            $table->string('images')->nullable();
            $table->string('salt')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('tax_id')->nullable();
            $table->integer('created_by_id');
            $table->timestamp('bill_date');
            $table->timestamp('expiry_date');
            $table->timestamps();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->index('name');
            $table->index('created_by_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('products');
    }
};
