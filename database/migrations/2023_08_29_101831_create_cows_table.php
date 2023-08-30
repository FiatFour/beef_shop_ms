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
        Schema::create('cows', function (Blueprint $table) {
            $table->id('cow_id');
            $table->string('cow_gene');
            $table->string('cow_img');
            $table->date('cow_birth');
            $table->unsignedBigInteger('sup_id');
            $table->foreign('sup_id')->references('sup_id')->on('suppliers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cows');
    }
};
