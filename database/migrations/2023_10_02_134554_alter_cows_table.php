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
        Schema::table('cows', function (Blueprint $table) {
            $table->foreignId('cow_gene_id')->constrained()->onDelete('cascade')->after('id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cows', function(Blueprint $table){
            $table->dropForeign('cow_gene_id');

        });
    }
};
