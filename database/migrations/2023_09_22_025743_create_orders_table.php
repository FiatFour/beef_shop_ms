<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->double('subtotal',10,2);
            $table->double('shipping',10,2);
            $table->string('coupon_code')->nullable();
            $table->double('discount',10,2)->nullable();
            $table->double('grand_total',10,2);

            // User Address related columns
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('mobile');
            // $table->string('district');
            // $table->foreignId('shipping_charges_id')->constrained()->onDelete('cascade');
            $table->string('address')->nullable();
            $table->string('apartment')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('orders', function (Blueprint $table) {
            // $table->dropForeign('customer_id');
            // $table->dropForeign('shipping_charges_id');
            // $table->dropForeign(['customer_id']);
            // $table->dropForeign(['shipping_charges_id']);
        // });
        Schema::dropIfExists('orders');
    }
};
