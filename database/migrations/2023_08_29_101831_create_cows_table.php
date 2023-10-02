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
            $table->id();
            // $table->string('gene');
            $table->double('weight',10,2);
            $table->double('height',10,2);
            $table->double('last_weight',10,2)->nullable();
            $table->double('last_height',10,2)->nullable();
            $table->double('dissect_total_kg',10,2)->nullable();
            $table->string('image')->nullable();
            $table->enum('gender',['Man', 'Woman']);
            $table->date('birth');
            $table->timestamp('dissect_date')->nullable();
            // $table->unsignedBigInteger('sup_id');
            // $table->foreign('sup_id')->references('id')->on('suppliers')->onDelete('cascade');
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
