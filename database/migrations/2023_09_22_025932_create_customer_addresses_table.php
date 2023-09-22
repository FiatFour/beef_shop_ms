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
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('mobile');
            // $table->string('district');
            // $table->foreignId('shipping_charges_id')->constrained()->onDelete('cascade');

            $table->string('address');
            $table->string('apartment')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('customer_addresses', function (Blueprint $table) {
        //     $table->dropForeign('customer_id');
        //     $table->dropForeign('shipping_charges_id');
        // });
        Schema::dropIfExists('customer_addresses');
    }
};
