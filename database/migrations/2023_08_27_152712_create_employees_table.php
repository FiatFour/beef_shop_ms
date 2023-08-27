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
            $table->id('emp_id');
            $table->string('emp_name',50);
            $table->string('emp_lname',50)->nullable();
            $table->string('emp_tel',33)->nullable();
            $table->string('email',100)->unique();
            $table->string('password');
            $table->string('emp_img')->nullable();
            $table->text('emp_address')->nullable();
            $table->tinyInteger('emp_gender')->nullable();
            $table->tinyInteger('emp_department')->nullable();
            $table->boolean('is_admin')->nullable();

            $table->integer('email_verified')->default(0);
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
