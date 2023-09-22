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
        Schema::table('customer_addresses', function (Blueprint $table) {

            $table->foreignId('shipping_charge_id')->constrained()->onDelete('cascade')->after('mobile');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_addresses', function(Blueprint $table){
            $table->dropForeign('shipping_charge_id');
        });
    }
};
