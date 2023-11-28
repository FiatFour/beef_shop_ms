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
        Schema::table('products', function (Blueprint $table) {
            // $table->enum('pre_order', ['Yes','No'])->default('No');
            $table->date('shipping_date')->nullable();
            $table->date('expire_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function(Blueprint $table){
            // $table->dropColumn('pre_order');
            $table->dropColumn('start_shipping');
            $table->dropColumn('expire');
        });
    }
};
