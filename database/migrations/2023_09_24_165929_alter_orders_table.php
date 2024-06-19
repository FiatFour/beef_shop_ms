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
        Schema::table('orders', function (Blueprint $table) {

            $table->foreignId('shipping_charge_id')->constrained()->onDelete('cascade')->after('mobile');
            $table->foreignId('discount_coupon_id')->constrained()->onDelete('cascade')->after('shipping')->nullable();
            $table->enum('payment_status', ['Paid', 'Not paid'])->after('grand_total')->default('Not paid');
            $table->enum('status', ['Pending', 'Shipped', 'Delivered', 'Cancelled'])->after('payment_status')->default('Pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function(Blueprint $table){
            $table->dropForeign('shipping_charge_id');
            $table->dropForeign('discount_coupon_id');
            $table->dropColumn('payment_status');
            $table->dropColumn('status');
        });
    }
};
