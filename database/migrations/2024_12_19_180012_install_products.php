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
            $table->string('mfg_id', 128)->nullable();
            $table->string('agency_id', 128)->nullable();
            $table->string('mfg_name', 128)->nullable();
            $table->string('agency_name', 128)->nullable();
            $table->string('slug', 128)->nullable();
            $table->decimal('price', 20, 6)->unsigned()->default(0);
            $table->decimal('mrp_price', 20, 6)->unsigned()->default(0);
            $table->string('quantity')->default(0);
            $table->string('hsn_code', 128)->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('salt')->nullable();
            $table->string('pkg', 125)->nullable();
            $table->string('bill_date')->nullable();
            $table->integer('state_id')->default(1);
            $table->integer('type_id')->nullable(); // 1 = Package
            $table->string('image')->nullable();
            $table->text('images')->nullable();
            $table->string('product_code', 128)->nullable();
            $table->string('batch_no', 128)->nullable();
            $table->text('description')->nullable();
            $table->decimal('distribution_price', 20, 6)->unsigned()->default(0);
            $table->integer('tax_id')->nullable();
            $table->integer('created_by_id');
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
