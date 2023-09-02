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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('emp_name');
            $table->string('emp_lname');
            $table->text('emp_address');
            $table->tinyInteger('emp_gender');
            $table->string('emp_tel');
            $table->string('emp_img');
            $table->string('emp_department');

            $table->string('email',);
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->boolean('email_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
