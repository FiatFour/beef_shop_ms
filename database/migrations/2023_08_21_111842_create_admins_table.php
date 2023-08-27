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
        Schema::create('admins', function (Blueprint $table) {
            $table->id('admin_id');
            $table->string('admin_name',50);
            $table->string('admin_lname',50)->nullable();
            $table->string('admin_tel',33)->nullable();
            $table->string('email',100)->unique();
            $table->string('password');
            $table->string('admin_img')->nullable();
            $table->text('admin_address')->nullable();
            $table->tinyInteger('admin_gender')->nullable();
            $table->tinyInteger('admin_department')->nullable();
            $table->boolean('super_admin')->nullable();

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
        Schema::dropIfExists('admins');
    }
};
